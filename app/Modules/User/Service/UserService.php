<?php

namespace App\Modules\User\Service;

use App\Modules\User\Repository\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $repository
    ) {}

    public function getAll()
    {
        return $this->repository->all();
    }

    public function getById(string $id)
    {
        return $this->repository->getById($id, ['department:id,name', 'userType:id,name', 'island:id,name', 'territory:id,name,island_id', 'territory.island:id,name', 'region:id,name,territory_id', 'region.territory:id,name,island_id', 'region.territory.island:id,name', 'branch:id,name,region_id', 'branch.region:id,name,territory_id', 'branch.region.territory:id,name,island_id', 'branch.region.territory.island:id,name']);
    }

    public function create(array $data)
    {
        return $this->repository->create($data)->load(['department:id,name', 'userType:id,name', 'island:id,name', 'territory:id,name,island_id', 'territory.island:id,name', 'region:id,name,territory_id', 'region.territory:id,name,island_id', 'region.territory.island:id,name', 'branch:id,name,region_id', 'branch.region:id,name,territory_id', 'branch.region.territory:id,name,island_id', 'branch.region.territory.island:id,name']);
    }

    public function update(string $id, array $data)
    {
        return $this->repository->update($id, $data)->load(['department:id,name', 'userType:id,name', 'island:id,name', 'territory:id,name,island_id', 'territory.island:id,name', 'region:id,name,territory_id', 'region.territory:id,name,island_id', 'region.territory.island:id,name', 'branch:id,name,region_id', 'branch.region:id,name,territory_id', 'branch.region.territory:id,name,island_id', 'branch.region.territory.island:id,name']);
    }

    public function delete(string $id)
    {
        return $this->repository->delete($id);
    }

}
