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
        ]);

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'username' => ['Invalid username or password.'],
            ]);
        }

        $request->session()->regenerate();

        $user = $request->user();

        if (!$user->active) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'username' => ['Your account is inactive.'],
            ]);
        }

        return response()->json([
            'message' => 'Login successful.',
        ]);
    }
}
