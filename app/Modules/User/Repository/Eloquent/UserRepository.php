<?php

namespace App\Modules\User\Repository\Eloquent;

use App\Models\User;
use App\Modules\BaseRepository;
use App\Modules\User\Repository\UserRepositoryInterface;
use Illuminate\Support\Collection;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * PermissionRepository constructor.
     *
     * @param User $model
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->with([
            'department:id,name',
            'userType:id,name',
            'island:id,name',
            'territory:id,name,island_id',
            'territory.island:id,name',
            'region:id,name,territory_id',
            'region.territory:id,name,island_id',
            'region.territory.island:id,name',
            'branch:id,name,region_id',
            'branch.region:id,name,territory_id',
            'branch.region.territory:id,name,island_id',
            'branch.region.territory.island:id,name',
        ])->get();
    }


}
