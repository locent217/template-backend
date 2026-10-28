<?php

namespace App\Http\Controllers;

use App\Modules\Permission\Service\PermissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(
        protected PermissionService $permissionService
    ) {}

    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        $user->loadMissing('department', 'userType');

        return response()->json([
            'id' => $user->id,
            'username' => $user->username,
            'first_name' => $user->first_name,
            'middle_name' => $user->middle_name,
            'last_name' => $user->last_name,
            'first_use' => (bool) $user->first_use,

            'department' => [
                'code' => $user->department?->code,
                'name' => $user->department?->name,
            ],

            'user_type' => [
                'code' => $user->userType?->code,
                'name' => $user->userType?->name,
            ],

            'permissions' => $this->permissionService
                ->getEffectivePermissions($user),
        ]);
    }

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            'remember' => ['sometimes', 'boolean'],
        ]);

        // Use Laravel's web guard for Sanctum SPA session and persistent remember-cookie auth.
        $remember = (bool) ($credentials['remember'] ?? false);
        unset($credentials['remember']);

        if (!Auth::guard('web')->attempt($credentials, $remember)) {
            app(\App\Support\Audit\AuditRecorder::class)->record('auth.login_failed', null, [], [], ['reason' => 'invalid_credentials']);
            throw ValidationException::withMessages([
                'username' => ['Invalid username or password.'],
            ]);
        }

        $request->session()->regenerate();

        $user = $request->user();

        if (!$user->active) {
            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            app(\App\Support\Audit\AuditRecorder::class)->record('auth.login_denied', null, [], [], ['reason' => 'inactive_account']);
            throw ValidationException::withMessages([
                'username' => ['Your account is inactive.'],
            ]);
        }

        app(\App\Support\Audit\AuditRecorder::class)->record('auth.login', $user);
        return response()->json([
            'message' => 'Login successful.',
        ]);
    }

    public function changeFirstUseCredentials(\App\Http\Requests\Auth\FirstUseCredentialsRequest $request, \App\Modules\User\Service\AccountService $accountService): JsonResponse
    {
        $accountService->changeFirstUseCredentials($request->user(), $request->validated());
        return response()->json(['message' => 'Credentials updated successfully.']);
    }

    public function logout(Request $request): JsonResponse
    {
        app(\App\Support\Audit\AuditRecorder::class)->record('auth.logout', $request->user());
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logout successful.',
        ]);
    }

}
