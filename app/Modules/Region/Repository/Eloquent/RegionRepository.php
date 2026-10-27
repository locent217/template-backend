<?php

namespace App\Modules\Region\Repository\Eloquent;

use App\Models\Region;
use App\Modules\BaseRepository;
use App\Modules\Region\Repository\RegionRepositoryInterface;
use Illuminate\Support\Collection;

class RegionRepository extends BaseRepository implements RegionRepositoryInterface
{
    /**
     * RegionRepository constructor.
     *
     * @param Region $model
     */
    public function __construct(Region $model)
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
