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
        return $this->model->all();
    }


}
