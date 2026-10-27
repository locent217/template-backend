<?php

namespace App\Modules\UserType\Repository\Eloquent;

use App\Models\UserType;
use App\Modules\BaseRepository;
use App\Modules\UserType\Repository\UserTypeRepositoryInterface;
use Illuminate\Support\Collection;

class UserTypeRepository extends BaseRepository implements UserTypeRepositoryInterface
{
    /**
     * UserTypeRepository constructor.
     *
     * @param UserType $model
     */
    public function __construct(UserType $model)
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
