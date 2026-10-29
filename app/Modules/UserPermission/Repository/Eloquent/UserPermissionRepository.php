<?php

namespace App\Modules\UserPermission\Repository\Eloquent;

use App\Models\Permission;
use App\Models\User;
use App\Models\UserPermission;
use App\Modules\BaseRepository;
use App\Modules\UserPermission\Repository\UserPermissionRepositoryInterface;
use Illuminate\Support\Collection;

class UserPermissionRepository extends BaseRepository implements UserPermissionRepositoryInterface
{
    /**
     * UserPermissionRepository constructor.
     *
     * @param UserPermission $model
     */
    public function __construct(UserPermission $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    public function getUserOverrides(User $user): Collection
    {
        return $this->model
            ->join(
                'permissions',
                'permissions.id',
                '=',
                'user_permissions.permission_id'
            )
            ->join(
                'modules',
                'modules.id',
                '=',
                'permissions.module_id'
            )
            ->where('user_permissions.user_id', $user->id)
            ->where('modules.active', true)
            ->select(
                'permissions.code',
                'modules.category',
                'user_permissions.allowed'
            )
            ->get();
    }

    public function getUserOverrideDetails(User $user): Collection
    {
        return $this->model
            ->join(
                'permissions',
                'permissions.id',
                '=',
                'user_permissions.permission_id'
            )
            ->where('user_permissions.user_id', $user->id)
            ->select(
                'permissions.id',
                'permissions.code',
                'user_permissions.allowed'
            )
            ->get();
    }

    public function saveUserOverride(
        User $user,
        Permission $permission,
        bool $allowed
    ): void {
        $this->model->updateOrCreate(
            [
                'user_id' => $user->id,
                'permission_id' => $permission->id,
            ],
            [
                'allowed' => $allowed,
                'updated_at' => now(),
            ]
        );
    }

    public function deleteUserOverride(
        User $user,
        Permission $permission
    ): void {
        $this->model
            ->where('user_id', $user->id)
            ->where('permission_id', $permission->id)
            ->delete();
    }
}
