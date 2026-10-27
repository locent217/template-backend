<?php

namespace App\Modules\Permission\Repository\Eloquent;

use App\Models\Permission;
use App\Modules\BaseRepository;
use App\Modules\Permission\Repository\PermissionRepositoryInterface;
use Illuminate\Support\Collection;

class PermissionRepository extends BaseRepository implements PermissionRepositoryInterface
{
    /**
     * PermissionRepository constructor.
     *
     * @param Permission $model
     */
    public function __construct(Permission $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->with(['module:id,name'])->get();
    }
}
