<?php

namespace App\Modules\Branch\Repository\Eloquent;

use App\Models\Branch;
use App\Modules\BaseRepository;
use App\Modules\Branch\Repository\BranchRepositoryInterface;
use Illuminate\Support\Collection;

class BranchRepository extends BaseRepository implements BranchRepositoryInterface
{
    /**
     * BranchRepository constructor.
     *
     * @param Branch $model
     */
    public function __construct(Branch $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->with(['region:id,name,territory_id', 'region.territory:id,name,island_id', 'region.territory.island:id,name'])->get();
    }
}
