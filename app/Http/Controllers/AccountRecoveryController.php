<?php
namespace App\Http\Controllers;

use App\Modules\User\Service\AccountRecoveryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class AccountRecoveryController extends Controller
{
    public function __construct(private AccountRecoveryService $service) {}

    public function forgotUsername(Request $request): JsonResponse
    {
        $data = $request->validate(['email' => ['required', 'email', 'max:255']]);
        $this->service->sendUsername($data['email']);
        return response()->json(['message' => 'If this email belongs to an active account, we have sent the username.']);
    }

    public function forgotPassword(Request $request): JsonResponse
    {
        $data = $request->validate(['email' => ['required', 'email', 'max:255']]);
        $this->service->requestPasswordOtp($data['email']);
        return response()->json(['message' => 'If this email belongs to an active account, we have sent a verification code.']);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'otp' => ['required', 'digits:6'],
            'password' => ['required', 'confirmed', Password::min(12)],
        ]);
        $this->service->resetPassword($data['email'], $data['otp'], $data['password']);
        return response()->json(['message' => 'Password updated. You may now sign in.']);
    }

    public function changePassword(Request $request): JsonResponse
    {
        $data = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', Password::min(12)],
        ]);
        $this->service->changePassword($request->user(), $data['current_password'], $data['password']);
        $request->session()->regenerate();
        return response()->json(['message' => 'Password updated successfully.']);
    }
}
