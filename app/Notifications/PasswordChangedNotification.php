<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class PasswordChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly string $method)
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
            'title' => 'Your password was changed',
            'message' => 'Your account password was changed successfully. If you did not make this change, contact your administrator immediately.',
            'type' => 'password_changed',
            'method' => $this->method,
            'url' => '/dashboard',
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        // Notify the browser to reload its authorized notifications feed.
        return (new BroadcastMessage(['refresh' => true, 'type' => 'password_changed']))
            ->onConnection('database')->onQueue('notifications');
    }
}
