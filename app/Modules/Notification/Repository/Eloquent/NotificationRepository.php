<?php
namespace App\Modules\Notification\Repository\Eloquent;
use App\Models\User;
use App\Modules\Notification\Repository\NotificationRepositoryInterface;
class NotificationRepository implements NotificationRepositoryInterface
{
    public function listFor(User $user, int $perPage): array
    {
        return [
            'items' => $user->notifications()->latest()->paginate($perPage),
            'unread_count' => $user->unreadNotifications()->count(),
        ];
    }
    public function mark(User $user, string $id, bool $read): bool
    {
        $notification = $user->notifications()->whereKey($id)->first();
        if (!$notification) return false;
        if ($read) $notification->markAsRead();
        else $notification->update(['read_at' => null]);
        return true;
    }
}
