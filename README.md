# Laravel 13 Backend — Developer README

> **Reusable Laravel API boilerplate** for a Vue 3 SPA. Includes session-based Sanctum authentication, Remember Me, role/permission-aware CRUD, dynamic Service–Repository modules, audit logs, database-queued notifications, Laravel Reverb, email onboarding, first-use credential setup, and account recovery.

## 1. Project overview and requirements

- PHP **8.3+** and extensions required by Laravel/MySQL; Composer; MySQL 8+; a supported Node.js version for the separate Vue app.
- **Clone the complete backend repository**. It contains the Laravel application, configuration, routes, migrations, and custom modules. Run the commands below from its root directory (where `composer.json` and `artisan` are located).
- The Vue frontend is a separate project, usually at `http://localhost:5173`; backend examples use `http://localhost:8000`.
- For local cross-origin Sanctum cookies, use consistent hostnames (e.g. **localhost for both**, not localhost for one and 127.0.0.1 for the other). Production SPA/API hosts should share the same registrable domain for stateful Sanctum SPA authentication.

## 2. First-time installation

From the **cloned Laravel backend repository root**:

```bash
composer install
cp .env.example .env             # Windows PowerShell: Copy-Item .env.example .env
php artisan key:generate
# Edit .env before migrating
php artisan optimize:clear
php artisan migrate
php artisan route:list
php artisan serve
```

The repository already contains the required Laravel application structure and package definitions. **Use `composer install` to install the locked dependencies**; do not create another Laravel project or merge a separate scaffold. Before running migrations against an existing database, review `php artisan migrate:status` and back up important data.

## 3. `.env` example (local development)

```dotenv
APP_NAME="Company Workspace"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
FRONTEND_URL=http://localhost:5173

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=company_workspace
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=false
SANCTUM_STATEFUL_DOMAINS=localhost:5173,localhost:8000

QUEUE_CONNECTION=database
DB_QUEUE=notifications
DB_QUEUE_RETRY_AFTER=90
BROADCAST_CONNECTION=reverb

REVERB_APP_ID=local-app-id
REVERB_APP_KEY=local-public-key
REVERB_APP_SECRET=replace-with-private-secret
REVERB_SERVER_HOST=0.0.0.0
REVERB_SERVER_PORT=8080
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http

MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=your-mailbox
MAIL_PASSWORD=your-app-password
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="${APP_NAME}"

AUDIT_ENABLED=true
```

- Use **real SMTP credentials**; never commit `.env` or email/Reverb secrets. For local email testing, use a local mail catcher or `MAIL_MAILER=log` (emails go to Laravel logs rather than recipients).
- `FRONTEND_URL` is the browser-facing Vue URL and is used by welcome/security email links; see `config/frontend.php`.
- The current `config/queue.php` uses `DB_QUEUE` as the **default queue name**. Explicit notification classes also select the `notifications` queue. A worker using only `default` will **not** process them.
- If using `SESSION_DRIVER=database`, ensure the `sessions` table exists. Likewise, database queue requires `jobs`/`failed_jobs` tables; check existing migrations before generating any.
- Sanctum stateful domains must list SPA origins **without protocol**. Configure `config/cors.php` to allow the exact frontend origin and `supports_credentials => true`. Frontend Axios must use `withCredentials` and CSRF cookies. Check `config/sanctum.php` and `config/session.php` for your environment.
- Production: `APP_DEBUG=false`, HTTPS, secure cookies, suitable `SESSION_DOMAIN=.example.com`, `SANCTUM_STATEFUL_DOMAINS=app.example.com,api.example.com`, explicit CORS origins, and `REVERB_SCHEME=https` behind a properly configured WSS reverse proxy. Use production domains, not localhost.

After changing `.env` or configuration:

```bash
php artisan optimize:clear
php artisan queue:restart
php artisan reverb:restart
```

## 4. Commands to run during development

Open separate terminals:

```bash
php artisan serve
php artisan queue:work
php artisan reverb:start
```

`--debug` is for local troubleshooting; omit it for normal production operation. `php artisan queue:work` alone uses `DB_QUEUE=notifications` and therefore does **not** consume the `default` queue. Use `--queue=notifications,default` to cover both.

Useful commands:

```bash
php artisan migrate:status
php artisan migrate
php artisan route:list --path=api
php artisan queue:failed
php artisan queue:retry all
php artisan optimize:clear
php artisan test
php artisan tinker
```

Only if the repository does not already contain the corresponding migration files, generate them with:

```bash
php artisan make:queue-table
php artisan make:queue-failed-table
php artisan make:session-table
php artisan migrate
```

Do not run `migrate:fresh` on production or a database with data. If using long-running reporting jobs, put them on a separate `reports` queue with its own worker, timeout, and `retry_after` greater than the worker timeout; do not block short notification jobs.

## 5. Module generator and automatic repository registration

```bash
php artisan make:module-model Product --module
```

Generates an Eloquent model plus:

```text
app/Models/Product.php
app/Modules/Product/Service/ProductService.php
app/Modules/Product/Repository/ProductRepositoryInterface.php
app/Modules/Product/Repository/Eloquent/ProductRepository.php
app/Providers/RepositoryServiceProviders/ProductRepositoryServiceProvider.php
```

The custom command lives in `app/Console/Commands/ModuleModelMakeCommand.php`, with templates in `stubs/modules/`. `app/Providers/RepositoryServiceProvider.php` scans `app/Providers/RepositoryServiceProviders/*RepositoryServiceProvider.php` and registers each provider **automatically**; ensure the parent provider is registered in `bootstrap/providers.php`. This is automatic *binding registration*, not automatic route/controller/migration creation.

After generation:

1. Implement repository database operations in `ProductRepository` and its interface.
2. Implement business rules in `ProductService` and inject the interface/service through Laravel's container.
3. Create a migration: `php artisan make:migration create_products_table`; edit it and run `php artisan migrate`.
4. Add controller, validation request(s), API routes, and permission codes; connect the Vue page/service.
5. Check `php artisan route:list` and test create/update/delete flows.

For manual modules, follow the same folder and provider naming convention. `AuditLog` and `Notification` already use Service–Repository layers.

## 6. Audit logs

```text
app/Modules/AuditLog/{Service,Repository/}
app/Http/Controllers/AuditLogController.php
app/Observers/AuditedModelObserver.php
app/Support/Audit/AuditableModelDiscovery.php
app/Providers/AppServiceProvider.php
config/audit.php
```

`AppServiceProvider::boot()` registers the observer for eligible models discovered by `AuditableModelDiscovery` when `config('audit.enabled', true)` is true. Prefer an enclosing positive `if` rather than an early `return`, so other boot logic can still execute. Audit routes are restricted by `AuthorizeAuditLogAccess`. Model observers record Eloquent model events, **not** raw SQL or mass `Model::where(...)->update()/delete()` operations. Exclude the audit model itself to avoid recursion; review masking of sensitive fields. Configure `AUDIT_ENABLED=false` to disable observer registration.

## 7. Notifications — implementation step by step

**Existing architecture:** MySQL `notifications` table + database queue + Laravel Reverb + Vue Echo/Pinia bell.

| File/directory | Purpose |
|---|---|
| `app/Models/User.php` | `Notifiable` trait; user recipient |
| `app/Notifications/UserCreatedNotification.php` | Example queued `database` + `broadcast` notification |
| `app/Notifications/PasswordChangedNotification.php` | Security alert notification |
| `app/Modules/Notification/Service/NotificationService.php` | Business logic, recipients, dispatch |
| `app/Modules/Notification/Repository/NotificationRepositoryInterface.php` | Data access contract |
| `app/Modules/Notification/Repository/Eloquent/NotificationRepository.php` | Notification query/read-state implementation |
| `app/Http/Controllers/NotificationController.php` | HTTP API |
| `routes/web.php` | Authenticated `/api/notifications` endpoints and `/broadcasting/auth` |
| `routes/channels.php` | Authorizes `App.Models.User.{id}` private channel |
| `database/migrations/*create_notifications_table.php` | Persistent notifications |
| `config/reverb.php`, `config/broadcasting.php`, `config/queue.php` | Broadcasting and queue configuration |

### A. Set up once

1. Ensure `laravel/reverb` and its configuration are installed; install only if missing: `composer require laravel/reverb -W`.
2. Ensure migrations for `notifications`, `jobs`, and `failed_jobs` exist; run `php artisan migrate`.
3. Configure the database queue, Reverb, Sanctum/CORS, and `.env` as above.
4. Start Laravel, the **queue worker**, and **Reverb** in separate terminals.
5. Log into Vue; its Echo client authorizes the private user channel through Sanctum cookies.

### B. Add a new notification type

```bash
php artisan make:notification OrderApprovedNotification
```

Example pattern (adapt payload/recipient to your feature):

```php
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class OrderApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $orderId)
    {
        $this->onConnection('database')->onQueue('notifications')->afterCommit();
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Order approved',
            'message' => 'Your order has been approved.',
            'type' => 'order_approved',
            'url' => '/dashboard',
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        // Signal only: Vue fetches authoritative records from the API.
        return (new BroadcastMessage(['refresh' => true, 'type' => 'order_approved']))
            ->onConnection('database')->onQueue('notifications');
    }
}
```

Dispatch after successful business action (and after the database transaction commits):

```php
$user->notify(new OrderApprovedNotification((string) $order->id));
```

The `User` model must use `Illuminate\Notifications\Notifiable`. The queue worker stores the notification and broadcasts a refresh signal; Vue refetches through the authenticated API. If changing payload fields, adjust the Vue bell renderer. Avoid sensitive information in notification bodies.

### C. API and debugging

```text
GET   /api/notifications
PATCH /api/notifications/{id}/read
PATCH /api/notifications/{id}/unread
```

Troubleshoot in this order: `php artisan queue:failed`, queue worker output, `php artisan reverb:start --debug`, browser Network → WS, `/broadcasting/auth` response, `routes/channels.php`, and Vue `VITE_REVERB_ENABLED=true`. A bell that updates only after refresh usually means the websocket, authorization, or worker isn't running. Remember: the queue is **named `notifications`**.

## 8. Email — configuration and adding a new template

**Existing files:**

```text
app/Mail/NewUserCredentialsMail.php
app/Mail/AccountRecoveryMail.php
app/Mail/PasswordChangedMail.php
resources/views/emails/new-user-credentials.blade.php
resources/views/emails/account-recovery.blade.php
resources/views/emails/password-changed.blade.php
config/frontend.php
```

1. Set `MAIL_MAILER`, SMTP host/port, username/password, sender address/name, and `FRONTEND_URL` in `.env`.
2. Run `php artisan optimize:clear`.
3. Create a mailable: `php artisan make:mail ExampleMail`.
4. Add its Blade view under `resources/views/emails/example.blade.php` (HTML email-safe layout; inline CSS is usually safest).
5. In the mailable, select the view and pass only the required display data. For example, `Mail::to($user->email)->send(new ExampleMail(...))`.
6. For background delivery, use `Mail::to(...)->queue(...)` **only** when the mailable implements `ShouldQueue` and you have a working queue worker. Check the queue it uses.
7. Test locally with a mail catcher or `MAIL_MAILER=log`; then verify actual SMTP delivery and spam placement.

**Existing flows:** creating a user sends a minimalist welcome email with name, temporary credentials, and frontend sign-in URL; forgot username emails the username to the registered address; forgot password emails an OTP; successful password change/reset/first-use setup sends a security email and in-app notification. The welcome-credentials mail is currently sent **synchronously** to avoid persisting plaintext credentials in a queue job. Prefer a single-use expiring account setup link instead of emailing temporary passwords for stronger security. Never include a password/OTP in audit logs or notifications.

## 9. Authentication, account recovery, and Remember Me

| Endpoint | Use |
|---|---|
| `POST /api/login` | Username/password, optional `remember: true` |
| `GET /api/me` | Restore authenticated user (including `first_use`) |
| `POST /api/logout` | End session and clear remember login |
| `POST /api/account/credentials` | Mandatory first-use credential update |
| `POST /api/account/change-password` | Logged-in password change |
| `POST /api/account/forgot-username` | Email username to registered email |
| `POST /api/account/forgot-password` | Email time-limited OTP |
| `POST /api/account/reset-password` | Validate OTP and change password |

`Auth::guard('web')->attempt($credentials, $remember)` enables Laravel's persistent remember cookie; the users table must contain `remember_token`. The frontend must **not** store credentials or remember tokens. First-use users are redirected to the credential setup page, with backend enforcement by `EnsureFirstUseCompleted`. Password-reset OTP records are stored in `password_reset_otps`; the current flow uses short expiration, attempt limits, and throttled endpoints. Test remember-cookie revocation and other active sessions after password resets as part of your security review.

## 10. Production deployment checklist

- Set HTTPS, valid trusted host/CORS/session/Sanctum domains, `APP_DEBUG=false`, real secrets, SMTP, WSS Reverb reverse proxy and permitted origins.
- Run `composer install --no-dev --optimize-autoloader`, `php artisan migrate --force`, `php artisan config:cache`, and `php artisan route:cache` after verifying routes are cacheable.
- Run the notification worker and Reverb **persistently** using Supervisor/systemd; restart workers on each deployment. Configure log rotation and monitoring.
- Back up MySQL; protect `.env`; validate file permissions; ensure rate limits, permissions, OTP expiry, audit redaction, and logout work.
- Test with two real browser sessions: new-user onboarding, remember me, email delivery, OTP reset, password-change alerts, websocket badge update, and permission restrictions.

## 11. Common problems

| Symptom | Check |
|---|---|
| `composer install` fails | Confirm full project root contains `composer.json`, PHP 8.3+, required extensions |
| `php artisan` missing | Verify you are running the command in the cloned backend repository root |
| Migrations fail | MySQL credentials, existing tables/duplicate migrations, database permissions |
| API 401 / 419 | Sanctum stateful domains, CSRF cookie, credentialed CORS, same-domain cookie setup |
| Jobs stay pending | `QUEUE_CONNECTION=database`, `--queue=notifications,default`, jobs migration, worker logs |
| No real-time bell update | Reverb process, WS connection, private-channel auth, public key, queue worker |
| Emails not received | SMTP settings, `MAIL_MAILER=log`, spam, server logs, sender domain |
| New module cannot resolve interface | Provider naming, parent provider in `bootstrap/providers.php`, `php artisan optimize:clear` |

For Vue setup and the frontend notification listener, see the **separate frontend README**.
