# Notifications — Step-by-Step Implementation Guide

**Project:** Laravel 13 + PHP 8.3 + MySQL + Sanctum SPA + Laravel Reverb + Vue 3 / Pinia

This is a **separate technical guide**. Keep the main `README.md` as the short installation guide. The examples and paths below reflect the notification implementation in this backend archive. Frontend snippets are illustrative where noted.

## 1. Understand the architecture

A user action (such as creating an account or changing a password) calls a Laravel service, which dispatches a `Notification`. The notification uses two channels:

- **`database`**: saves a permanent notification in MySQL's `notifications` table.
- **`broadcast`**: sends a lightweight real-time signal through Laravel Reverb. The Vue application then reloads the authorized notification list.

Because these notifications implement `ShouldQueue`, the **database queue worker** performs delivery. The **Reverb server** maintains WebSocket connections. **Laravel Echo** receives broadcasts, **Pinia** stores UI state, and the **notification bell** renders it.

```
User action -> Service -> Notification -> database queue jobs
                                         |-> database channel -> notifications table
                                         |-> broadcast channel -> Reverb -> Echo -> Pinia refresh
Vue bell -> authenticated GET /api/notifications -> MySQL notifications
```

**Important:** `jobs` contains pending work; `notifications` contains delivered messages. Reverb is a delivery transport, not a permanent message store.

## 2. Install and configure dependencies

Run `composer install` in the backend project and `npm install laravel-echo pusher-js` if not installed on the frontend project, `npm install` if the frontend project already has laravel echo and pusher js. This project already includes the notification code; you do not need to regenerate it. If integrating into another project, install and configure Laravel Reverb, Laravel Echo, and `pusher-js` using the versions compatible with your Laravel/Vue applications.

Backend `.env` example (replace placeholders):

```dotenv
APP_URL=http://localhost:8000
FRONTEND_URL=http://localhost:5173
QUEUE_CONNECTION=database
DB_QUEUE=notifications
DB_QUEUE_RETRY_AFTER=90
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-app-key
REVERB_APP_SECRET=keep-this-secret
REVERB_SERVER_HOST=0.0.0.0
REVERB_SERVER_PORT=8080
REVERB_HOST=127.0.0.1
REVERB_PORT=8080
REVERB_SCHEME=http
```

The `REVERB_APP_KEY` is used by the client; **never expose `REVERB_APP_SECRET`**. The API HTTP port and Reverb WebSocket port are separate. Configure Sanctum stateful domains, session cookies, and CORS for your actual Vue origin. For cross-origin broadcasting authorization, ensure `broadcasting/auth` is included in `config/cors.php`'s `paths`, along with `api/*` and `sanctum/csrf-cookie`; restrict `allowed_origins` to trusted origins when `supports_credentials` is enabled.

Frontend `.env` example:

```dotenv
VITE_REVERB_ENABLED=true
VITE_REVERB_APP_KEY=your-app-key
VITE_REVERB_HOST=localhost
VITE_REVERB_PORT=8080
VITE_REVERB_SCHEME=http
```

Use the API URL environment variable already expected by your frontend Axios service; do not hardcode `localhost:8000` for `/broadcasting/auth` if your Laravel server uses another port.

## 3. Create the required database tables

The backend already includes `database/migrations/2026_10_27_000003_create_notifications_table.php`. It defines:

| Column | Meaning |
|---|---|
| `id` | UUID notification ID |
| `type` | Laravel notification class name |
| `notifiable_type`, `notifiable_id` | Polymorphic recipient (the User model and its UUID) |
| `data` | JSON payload displayed by the frontend |
| `read_at` | `NULL` when unread; timestamp when read |
| `created_at`, `updated_at` | Timestamps |

The existing project also has the database queue migrations for `jobs` and `failed_jobs`. Run:

```bash
php artisan migrate
```

For a brand-new, disposable local database only, the main README documents `php artisan migrate:fresh --seed`. **Never run `migrate:fresh` on production or on a database containing data you need.** Do not generate duplicate queue/notification migrations if they already exist.

## 4. Enable notifications on the User model

`app/Models/User.php` uses Laravel's `Notifiable` trait:

```php
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
}
```

The trait provides `$user->notify(...)`, `$user->notifications()`, and `$user->unreadNotifications()`. Keep the model's existing traits and other code; the snippet shows only the relevant part.

## 5. Define a notification class

**Existing file:** `app/Notifications/UserCreatedNotification.php`

To create a new type from scratch:

```bash
php artisan make:notification TicketCreatedNotification
```

The existing user-created notification is structured like this:

```php
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class UserCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly string $newUserName,
        private readonly bool $isNewUser
    ) {
        $this->onConnection('database')
            ->onQueue('notifications')
            ->afterCommit();
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->isNewUser ? 'Welcome to the system' : 'New colleague in your department',
            'message' => $this->isNewUser
                ? 'Your account has been created.'
                : $this->newUserName . ' has joined your department.',
            'type' => 'user_created',
            'url' => '/dashboard',
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return (new BroadcastMessage([
            'refresh' => true,
            'type' => 'user_created',
        ]))->onConnection('database')->onQueue('notifications');
    }
}
```

This excerpt slightly shortens the original welcome message. **`ShouldQueue`** puts delivery into the queue. **`afterCommit()`** avoids dispatching before an enclosing database transaction commits. **`via()`** chooses the two delivery channels. **`toArray()`** supplies the saved JSON. **`toBroadcast()`** sends only a refresh signal rather than sensitive notification contents.

Another existing example is `app/Notifications/PasswordChangedNotification.php`. **Never include passwords, OTPs, session secrets, or tokens in notification data or broadcasts.**

## 6. Dispatch notifications from the Service layer

**Existing file:** `app/Modules/Notification/Service/NotificationService.php`

The implementation calls:

```php
$newUser->notify(new UserCreatedNotification($fullName, true));
```

It also notifies active colleagues in the same department in chunks of 100. To send a new notification to a user, inject or resolve the relevant application service, identify the authorized recipient, and call `$user->notify(new YourNotification(...))` after the business operation succeeds.

**Separate concern:** The current new-user credentials email is sent synchronously through `Mail::send(...)`; it is not the same as the database/broadcast notification. The implementation intentionally avoids placing temporary passwords into queued job payloads. For stronger onboarding security, use one-time password setup links rather than emailing temporary passwords.

## 7. Authorize private WebSocket channels

**Existing file:** `routes/channels.php`

```php
Broadcast::channel('App.Models.User.{id}', function (User $user, string $id): bool {
    return (string) $user->getKey() === $id;
});
```

Only the authenticated user with the matching UUID can subscribe to that private channel. The backend's `routes/web.php` registers broadcasting authentication using the `web` and `auth:sanctum` middleware:

```php
Broadcast::routes(['middleware' => ['web', 'auth:sanctum']]);
```

The Vue client must authorize subscriptions using its authenticated Axios/session setup. **Do not use public channels for private user notifications.**

## 8. Expose authenticated notification APIs

**Existing backend files:**

- `app/Http/Controllers/NotificationController.php`
- `app/Modules/Notification/Service/NotificationService.php`
- `app/Modules/Notification/Repository/NotificationRepositoryInterface.php`
- `app/Modules/Notification/Repository/Eloquent/NotificationRepository.php`

The current API routes in `routes/web.php` are:

| Method | Path | Behavior |
|---|---|---|
| `GET` | `/api/notifications` | Paginated items and unread count |
| `PATCH` | `/api/notifications/{id}/read` | Mark a notification as read |
| `PATCH` | `/api/notifications/{id}/unread` | Mark it as unread |

The controller delegates to the service, and the service delegates to the repository. The repository reads through **`$user->notifications()`**, so a user cannot fetch another user's notifications by supplying another UUID. Its `mark()` method first finds the notification through the authenticated user's relationship, then calls `markAsRead()` or sets `read_at` to `null`.

Example list response shape:

```json
{
  "items": {
    "data": [{"id": "notification-uuid", "data": {"title": "Welcome", "message": "Your account was created."}, "read_at": null}],
    "current_page": 1,
    "last_page": 1
  },
  "unread_count": 1
}
```

This is an illustrative shortened response; Laravel pagination includes additional fields.

## 9. Connect Vue using Echo and Pinia

**Frontend implementation files:** `src/services/realtime.ts`, `src/stores/notification.ts`, and `src/components/notifications/NotificationBell.vue`.

1. Initialize Laravel Echo with `pusher-js`, using the public Reverb key, host, port, and scheme.
2. Authorize private-channel subscriptions using the existing Axios client, Sanctum cookies, and CSRF handling.
3. Subscribe to `App.Models.User.${userId}` after login.
4. On the notification callback, call the Pinia notification store's refresh action, which requests `GET /api/notifications`.
5. Display `items` and `unreadCount` in the Vuetify notification bell.
6. Call the PATCH read/unread endpoints when the user changes notification state.
7. Unsubscribe and clear notification state on logout or account switch.

Conceptual Echo subscription (not a replacement for the existing `realtime.ts` setup):

```ts
echo.private(`App.Models.User.${userId}`)
    .notification(() => notificationStore.refresh())
```

The current frontend also uses a **60-second polling fallback** while the browser tab is visible. This allows notification updates even if Reverb temporarily disconnects.

## 10. Run the background processes

Use **separate terminals** from the backend project root:

```bash
php artisan serve
```

```bash
php artisan queue:work
```

```bash
php artisan reverb:start
```

Start Vue separately with `npm run dev`. If your queue only needs `notifications`, use `--queue=notifications`; include `default` if other jobs also use it. Ensure the queue retry-after value exceeds the worker timeout. In production, use Supervisor or systemd to keep the queue worker and Reverb running, and run `php artisan queue:restart` after deploying worker code changes.

**Note:** The main README's plain `php artisan queue:work` is sufficient when the configured default queue name is `notifications`; the explicit command above shows how to process both queues.

## 11. Test end to end

**Test A — Database and queue**

1. Start the queue worker and Reverb.
2. Create a test user through the application, or open Tinker:

   ```bash
   php artisan tinker
   ```

   ```php
   $user = \App\Models\User::firstOrFail();
   $user->notify(new \App\Notifications\UserCreatedNotification('Test User', true));
   ```

3. Inspect pending jobs and the resulting notifications:

   ```sql
   SELECT id, notifiable_id, data, read_at, created_at
   FROM notifications
   ORDER BY created_at DESC
   LIMIT 10;
   ```

4. Log in as the recipient and confirm that the bell displays the message.
5. Mark it as read and verify `read_at` is populated; mark it unread and verify `read_at` becomes `NULL`.

**Test B — Real time**

Keep two browser windows open, trigger a notification, and verify that the recipient's bell updates without a manual refresh. If it appears only after polling, inspect the Reverb process, WebSocket connection, and private-channel authorization.

## 12. Troubleshooting

| Symptom | Investigate |
|---|---|
| Nothing is inserted into `notifications` | Is the queue worker running? Check `jobs`, `failed_jobs`, and `php artisan queue:failed`. |
| Stored in MySQL but not shown in Vue | Check authenticated `GET /api/notifications`, current user, and Pinia refresh. |
| Appears only after 60 seconds | Reverb connection or Echo subscription may be failing. |
| `/broadcasting/auth` returns 401/419 | Check Sanctum stateful domains, CSRF, session cookies, and Axios credentials. |
| `/broadcasting/auth` has CORS errors | Confirm correct Laravel API port and CORS `broadcasting/auth` path. |
| Subscription returns 403 | Confirm authenticated UUID matches the private channel UUID. |
| Reverb connects but no event arrives | Check `BROADCAST_CONNECTION=reverb`, Reverb credentials, queue worker, and channel name. |
| Jobs time out | Separate short notification jobs from long-running report jobs; configure worker timeout/retry-after appropriately. |

For debugging:

```bash
php artisan queue:failed
php artisan queue:work database --queue=notifications,default -v
php artisan reverb:start --debug
php artisan optimize:clear
```

## 13. Production and security checklist

- Protect all notification APIs with Sanctum and scope reads/updates to the authenticated user.
- Use private channels and enforce matching recipient UUIDs.
- Use HTTPS/WSS, trusted CORS origins, secure cookies, and correct Sanctum domain settings.
- Keep `REVERB_APP_SECRET`, SMTP credentials, and all other secrets server-side.
- Monitor queue backlog and failed jobs; supervise workers and Reverb.
- Do not broadcast confidential data; broadcast a refresh signal and retrieve data through the authorized API.
- Avoid storing passwords or OTPs in database notification payloads, logs, or queued mail jobs.
- Test notification behavior during maintenance mode; the maintenance middleware may return 503 to normal users.

**Summary:** Laravel Notifications define and queue the event; MySQL persists it; Reverb broadcasts the signal; Echo listens; Pinia reloads the user's authorized notification feed; the Vue bell renders the result.
