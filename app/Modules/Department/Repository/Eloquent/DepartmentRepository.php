<?php

namespace App\Modules\Department\Repository\Eloquent;

use App\Models\Department;
use App\Modules\BaseRepository;
use App\Modules\Department\Repository\DepartmentRepositoryInterface;
use Illuminate\Support\Collection;

class DepartmentRepository extends BaseRepository implements DepartmentRepositoryInterface
{
    /**
     * DepartmentRepository constructor.
     *
     * @param Department $model
     */
    public function __construct(Department $model)
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
}
