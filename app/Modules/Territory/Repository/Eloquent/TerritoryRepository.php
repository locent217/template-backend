<?php

namespace App\Modules\Territory\Repository\Eloquent;

use App\Models\Territory;
use App\Modules\BaseRepository;
use App\Modules\Territory\Repository\TerritoryRepositoryInterface;
use Illuminate\Support\Collection;

class TerritoryRepository extends BaseRepository implements TerritoryRepositoryInterface
{
    /**
     * TerritoryRepository constructor.
     *
     * @param Territory $model
     */
    public function __construct(Territory $model)
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
