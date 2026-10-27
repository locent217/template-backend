<?php

namespace App\Modules\DepartmentUserTypePermission\Repository\Eloquent;

use App\Models\DepartmentUserTypePermission;
use App\Models\User;
use App\Modules\BaseRepository;
use App\Modules\DepartmentUserTypePermission\Repository\DepartmentUserTypePermissionRepositoryInterface;
use Illuminate\Support\Collection;

class DepartmentUserTypePermissionRepository extends BaseRepository implements DepartmentUserTypePermissionRepositoryInterface
{
    /**
     * DepartmentUserTypePermissionRepository constructor.
     *
     * @param DepartmentUserTypePermission $model
     */
    public function __construct(DepartmentUserTypePermission $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->with(['department:id,name', 'userType:id,name', 'permission:id,name,code,module_id', 'permission.module:id,name'])->get();
    }

    public function getDefaultPermissions(User $user): Collection
    {
        return $this->model
            ->join(
                'permissions',
                'permissions.id',
                '=',
                'department_user_type_permissions.permission_id'
            )
            ->join(
                'modules',
                'modules.id',
                '=',
                'permissions.module_id'
            )
            ->where(
                'department_user_type_permissions.department_id',
                $user->department_id
            )
            ->where(
                'department_user_type_permissions.user_type_id',
                $user->user_type_id
            )
            ->where('modules.active', true)
            ->select('permissions.code', 'modules.category')
            ->get();
    }

    public function getDefaultPermissionCodes(User $user): Collection
    {
        return $this->getDefaultPermissions($user)
            ->pluck('code');
    }
}
