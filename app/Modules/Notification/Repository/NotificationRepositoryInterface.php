<?php
namespace App\Modules\Notification\Repository;
use App\Models\User;
interface NotificationRepositoryInterface
{
    public function listFor(User $user, int $perPage): array;
    public function mark(User $user, string $id, bool $read): bool;
}
