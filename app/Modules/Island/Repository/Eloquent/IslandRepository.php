<?php

namespace App\Modules\Island\Repository\Eloquent;

use App\Models\Island;
use App\Modules\BaseRepository;
use App\Modules\Island\Repository\IslandRepositoryInterface;
use Illuminate\Support\Collection;

class IslandRepository extends BaseRepository implements IslandRepositoryInterface
{
    /**
     * IslandRepository constructor.
     *
     * @param Island $model
     */
    public function __construct(Island $model)
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
