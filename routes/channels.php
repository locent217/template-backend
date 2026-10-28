<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

// Laravel's default Notifiable channel for App\Models\User.
// Strict UUID comparison prevents users subscribing to other accounts.
Broadcast::channel('App.Models.User.{id}', function (User $user, string $id): bool {
    return (string) $user->getKey() === $id;
});
