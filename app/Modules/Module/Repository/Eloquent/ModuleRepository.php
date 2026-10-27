<?php

namespace App\Modules\Module\Repository\Eloquent;

use App\Models\Module;
use App\Modules\BaseRepository;
use App\Modules\Module\Repository\ModuleRepositoryInterface;
use Illuminate\Support\Collection;

class ModuleRepository extends BaseRepository implements ModuleRepositoryInterface
{
    /**
     * ModuleRepository constructor.
     *
     * @param Module $model
     */
    public function __construct(Module $model)
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
