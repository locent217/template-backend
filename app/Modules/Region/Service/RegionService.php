<?php

namespace App\Modules\Region\Service;

use App\Modules\Region\Repository\RegionRepositoryInterface;

class RegionService
{
    public function __construct(
        protected RegionRepositoryInterface $modelRepository
    ) {}

    public function all(): array
    {
        return $this->modelRepository->all()->toArray();
    }

    public function getById(string $id)
    {
        return $this->modelRepository->getById($id, ['territory:id,name,island_id', 'territory.island:id,name']) ?? abort(404, 'Record not found.');
    }

    public function create(array $data)
    {
        return $this->modelRepository->create($data)->load(['territory:id,name,island_id', 'territory.island:id,name']);
    }

    public function update(string $id, array $data)
    {
        return $this->modelRepository->update($id, $data)->load(['territory:id,name,island_id', 'territory.island:id,name']);
    }

    public function delete(string $id)
    {
        return $this->modelRepository->delete($id);
    }
}
