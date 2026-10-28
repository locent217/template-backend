<?php

namespace App\Modules\User\Service;

use App\Mail\PasswordChangedMail;
use App\Models\User;
use App\Notifications\PasswordChangedNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PasswordChangeAlertService
{
    /**
     * Call only after a successful password write has committed.
     * No password, OTP, or other credential is included in the alert.
     */
    public function send(User $user, string $method): void
    {
        try {
            $user->notify(new PasswordChangedNotification($method));
        } catch (\Throwable $exception) {
            Log::error('Password change notification dispatch failed', [
                'user_id' => $user->getKey(), 'exception_type' => get_class($exception),
            ]);
        }

        try {
            Mail::to($user->email)->send(new PasswordChangedMail(
                trim($user->first_name . ' ' . $user->last_name) ?: 'there',
                $method
            ));
        } catch (\Throwable $exception) {
            // The password change must remain successful even if SMTP is temporarily unavailable.
            Log::error('Password change security email failed', [
                'user_id' => $user->getKey(), 'exception_type' => get_class($exception),
            ]);
        }
    }
}
