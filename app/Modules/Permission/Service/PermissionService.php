<?php

namespace App\Modules\Permission\Service;

use App\Models\Permission;
use App\Models\User;
use App\Modules\DepartmentUserTypePermission\Repository\DepartmentUserTypePermissionRepositoryInterface;
use App\Modules\Permission\Repository\PermissionRepositoryInterface;
use App\Modules\UserPermission\Repository\UserPermissionRepositoryInterface;
use Illuminate\Support\Facades\DB;

class PermissionService
{
    public function __construct(
        protected PermissionRepositoryInterface $modelRepository,
        protected DepartmentUserTypePermissionRepositoryInterface $departmentUserTypePermissionRepository,
        protected UserPermissionRepositoryInterface $userPermissionRepository
    ) {}

    public function all(): array
    {
        return $this->modelRepository->all()->toArray();
    }

    public function getEffectivePermissions(User $user): array
    {
        if (!$user->active) {
            return [];
        }

        $user->loadMissing('userType');

        // Super Admin bypasses department defaults and overrides.
        if ($user->userType?->code === 'SUPER_ADMIN') {
            return DB::table('permissions')
                ->join(
                    'modules',
                    'modules.id',
                    '=',
                    'permissions.module_id'
                )
                ->where('modules.active', true)
                ->pluck('permissions.code')
                ->all();
        }

        // Get default Department + User Type permissions.
        $permissions = $this->departmentUserTypePermissionRepository
            ->getDefaultPermissions($user)
            ->keyBy('code');

        // Apply individual user grants and denials.
        $overrides = $this->userPermissionRepository->getUserOverrides($user);

        foreach ($overrides as $override) {
            if ((bool) $override->allowed) {
                $permissions->put($override->code, $override);
            } else {
                $permissions->forget($override->code);
            }
        }

        // Apply restrictions that individual overrides cannot bypass.
        $permissions = $permissions->filter(
            fn ($permission) => $this->passesMandatoryRestrictions(
                $user,
                $permission
            )
        );

        // Require module.view for other module actions.
        return $permissions
            ->keys()
            ->filter(function ($code) use ($permissions) {
                $position = strrpos($code, '.');

                if ($position === false) {
                    return false;
                }

                $action = substr($code, $position + 1);

                if ($action === 'view') {
                    return true;
                }

                $moduleCode = substr($code, 0, $position);

                return $permissions->has("{$moduleCode}.view");
            })
            ->values()
            ->all();
    }

    public function hasPermission(
        User $user,
        string $permissionCode
    ): bool {
        return in_array(
            $permissionCode,
            $this->getEffectivePermissions($user),
            true
        );
    }

    protected function passesMandatoryRestrictions(
        User $user,
        object $permission
    ): bool {
        $isNonHeadOffice =
            $user->branch_id !== null ||
            $user->region_id !== null ||
            $user->territory_id !== null ||
            $user->island_id !== null;

        $isReport = $permission->category === 'report';
        $isView = str_ends_with($permission->code, '.view');

        // All Non-Head Office employees are report-view-only.
        if ($isNonHeadOffice) {
            return $isReport && $isView;
        }

        // Head Office Staff cannot modify reports.
        if (
            $user->userType?->code === 'STAFF' &&
            $isReport
        ) {
            return $isView;
        }

        return true;
    }

    public function getUserPermissionDetails(User $user): array
    {
        $user->loadMissing('department', 'userType');

        return [
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'department' => $user->department?->code,
                'user_type' => $user->userType?->code,
            ],

            'default_permissions' => $this->departmentUserTypePermissionRepository
                ->getDefaultPermissionCodes($user),

            'overrides' => $this->userPermissionRepository
                ->getUserOverrideDetails($user),

            'effective_permissions' => $this->getEffectivePermissions($user),
        ];
    }

    public function saveUserPermission(
        User $user,
        Permission $permission,
        bool $allowed
    ): array {
        $user->loadMissing('userType');

        // Super Admin permissions cannot be overridden.
        if ($user->userType?->code === 'SUPER_ADMIN') {
            abort(403, 'Super Admin permissions cannot be overridden.');
        }

        // Do not allow individual grants for inactive modules.
        if (!$permission->module()->where('active', true)->exists()) {
            abort(422, 'Permission belongs to an inactive module.');
        }

        // Permission-management delegation is Super Admin only.
        if ($permission->code === 'users.manage_permissions' && $allowed) {
            abort(403, 'This permission cannot be delegated.');
        }

        $this->userPermissionRepository->saveUserOverride(
            $user,
            $permission,
            $allowed
        );

        return $this->getEffectivePermissions($user);
    }

    public function deleteUserPermission(
        User $user,
        Permission $permission
    ): array {
        $user->loadMissing('userType');

        if ($user->userType?->code === 'SUPER_ADMIN') {
            abort(403, 'Super Admin permissions cannot be overridden.');
        }

        $this->userPermissionRepository->deleteUserOverride(
            $user,
            $permission
        );

        return $this->getEffectivePermissions($user);
    }
}
