<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly string $newUserName, private readonly bool $isNewUser)
    {
        $this->onConnection('database')->onQueue('notifications')->afterCommit();
    }

    public function via(object $notifiable): array { return ['database', 'broadcast']; }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->isNewUser ? 'Welcome to the system' : 'New colleague in your department',
            'message' => $this->isNewUser
                ? 'Your account has been created. Check your email for your temporary sign-in credentials.'
                : $this->newUserName . ' has joined your department.',
            'type' => 'user_created',
            'url' => '/dashboard',
        ];
    }
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        // Broadcast only a signal. The authenticated client fetches the latest
        // data through the existing, authorization-protected notifications API.
        return (new BroadcastMessage(['refresh' => true, 'type' => 'user_created']))
            ->onConnection('database')
            ->onQueue('notifications');
    }
}
