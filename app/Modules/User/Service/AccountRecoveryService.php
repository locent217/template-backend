<?php

namespace App\Modules\User\Service;

use App\Models\User;
use App\Mail\AccountRecoveryMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AccountRecoveryService
{
    public function sendUsername(string $email): void
    {
        $user = User::query()->where('email', $email)->where('active', true)->first();
        if ($user) {
            Mail::to($user->email)->send(new AccountRecoveryMail('username', $user->first_name ?: 'there', $user->username));
        }
    }

    public function requestPasswordOtp(string $email): void
    {
        $user = User::query()->where('email', $email)->where('active', true)->first();
        if (!$user) return;

        // Only one live code per address; avoid rapid resends even if requests come from different IPs.
        $existing = DB::table('password_reset_otps')->where('email', $email)->first();
        if ($existing && now()->diffInSeconds($existing->created_at, false) > -60) return;

        $otp = (string) random_int(100000, 999999);
        DB::table('password_reset_otps')->updateOrInsert(['email' => $email], [
            'otp_hash' => Hash::make($otp),
            'attempts' => 0,
            'expires_at' => now()->addMinutes(5),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Mail::to($user->email)->send(new AccountRecoveryMail('otp', $user->first_name ?: 'there', null, $otp));
    }

    public function resetPassword(string $email, string $otp, string $newPassword): void
    {
        $user = DB::transaction(function () use ($email, $otp, $newPassword) {
            $row = DB::table('password_reset_otps')->where('email', $email)->lockForUpdate()->first();
            if (!$row || now()->greaterThanOrEqualTo($row->expires_at) || $row->attempts >= 5) {
                throw ValidationException::withMessages(['otp' => ['Invalid or expired verification code. Request a new code.']]);
            }
            DB::table('password_reset_otps')->where('email', $email)->increment('attempts');
            if (!Hash::check($otp, $row->otp_hash)) {
                throw ValidationException::withMessages(['otp' => ['Invalid or expired verification code.']]);
            }
            $user = User::query()->where('email', $email)->where('active', true)->lockForUpdate()->first();
            if (!$user) throw ValidationException::withMessages(['otp' => ['Invalid or expired verification code.']]);
            if (Hash::check($newPassword, $user->password)) {
                throw ValidationException::withMessages(['password' => ['Choose a password different from your current password.']]);
            }
            $user->forceFill(['password' => $newPassword, 'remember_token' => Str::random(60)])->save();
            DB::table('password_reset_otps')->where('email', $email)->delete();
            return $user;
        });
        app(PasswordChangeAlertService::class)->send($user, 'recovery');
    }

    public function changePassword(User $user, string $currentPassword, string $newPassword): void
    {
        if (!Hash::check($currentPassword, $user->password)) {
            throw ValidationException::withMessages(['current_password' => ['Current password is incorrect.']]);
        }
        if (Hash::check($newPassword, $user->password)) {
            throw ValidationException::withMessages(['password' => ['Choose a different password.']]);
        }
        $user->forceFill(['password' => $newPassword, 'remember_token' => Str::random(60)])->save();
        app(PasswordChangeAlertService::class)->send($user, 'account_settings');
    }
}
