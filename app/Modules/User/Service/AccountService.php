<?php
namespace App\Modules\User\Service;

use App\Models\User;
use App\Support\Audit\AuditRecorder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AccountService
{
    public function changeFirstUseCredentials(User $user, array $data): void
    {
        if (!$user->first_use) {
            throw ValidationException::withMessages(['username' => ['Initial credential setup has already been completed.']]);
        }
        if (!Hash::check($data['current_password'], $user->password)) {
            throw ValidationException::withMessages(['current_password' => ['The current password is incorrect.']]);
        }
        if (Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages(['password' => ['Choose a password different from your temporary password.']]);
        }
        DB::transaction(function () use ($user, $data) {
            $user->forceFill([
                'username' => $data['username'],
                'password' => $data['password'], // User model's hashed cast
                'first_use' => false,
            ])->save();
            app(AuditRecorder::class)->record('auth.first_use_credentials_changed', $user, [], [], [
                'service_class' => static::class, 'service_method' => __FUNCTION__,
            ]);
        });
        // Revalidate the authenticated session after changing credentials.
        Auth::guard('web')->login($user);
        request()->session()->regenerate();
        app(PasswordChangeAlertService::class)->send($user, 'first_use');
    }
}
