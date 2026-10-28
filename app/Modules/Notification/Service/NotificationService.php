<?php
namespace App\Modules\Notification\Service;
use App\Mail\NewUserCredentialsMail;
use App\Models\User;
use App\Modules\Notification\Repository\NotificationRepositoryInterface;
use App\Notifications\UserCreatedNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
class NotificationService
{
    public function __construct(private NotificationRepositoryInterface $repository) {}
    public function listFor(User $user, int $perPage = 15): array
    {
        return $this->repository->listFor($user, min(max($perPage, 1), 50));
    }
    public function mark(User $user, string $id, bool $read): bool
    {
        return $this->repository->mark($user, $id, $read);
    }
    public function notifyNewUser(User $newUser, string $temporaryPassword): void
    {
        $fullName = trim($newUser->first_name . ' ' . $newUser->last_name);
        // Never include passwords in the database notification payload.
        $newUser->notify(new UserCreatedNotification($fullName, true));
        if ($newUser->department_id) {
            User::query()->where('department_id', $newUser->department_id)
                ->whereKeyNot($newUser->getKey())
                ->where('active', true)
                ->select(['id', 'department_id'])
                ->chunkById(100, function ($users) use ($fullName) {
                    foreach ($users as $user) {
                        $user->notify(new UserCreatedNotification($fullName, false));
                    }
                });
        }
        // Keep credentials out of persistent queue payloads. SMTP remains synchronous
        // until onboarding is migrated to a one-time password setup link.
        try {
            Mail::to($newUser->email)->send(new NewUserCredentialsMail($newUser->username, $temporaryPassword, $fullName));
        } catch (\Throwable $exception) {
            // Do not log the credentials. Account creation remains successful; operators can retry onboarding securely.
            Log::error('New user credential email failed', ['user_id' => $newUser->getKey(), 'exception_type' => get_class($exception)]);
        }
    }
}
