<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\User;
use App\Modules\Permission\Service\PermissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserPermissionController extends Controller
{
    public function __construct(
        protected PermissionService $permissionService
    ) {}

    public function index(User $user): JsonResponse
    {
        return response()->json(
            $this->permissionService->getUserPermissionDetails($user)
        );
    }

    public function update(
        Request $request,
        User $user,
        Permission $permission
    ): JsonResponse {
        $validated = $request->validate([
            'allowed' => ['required', 'boolean'],
        ]);

        $permissions = $this->permissionService
            ->saveUserPermission(
                $user,
                $permission,
                (bool) $validated['allowed']
            );

        return response()->json([
            'message' => 'User permission override saved.',
            'effective_permissions' => $permissions,
        ]);
    }

    public function destroy(
        User $user,
        Permission $permission
    ): JsonResponse {
        $permissions = $this->permissionService
            ->deleteUserPermission(
                $user,
                $permission
            );

        return response()->json([
            'message' => 'Permission override removed.',
            'effective_permissions' => $permissions,
        ]);
    }
}
