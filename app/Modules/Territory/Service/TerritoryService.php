<?php

namespace App\Modules\Territory\Service;

use App\Modules\Territory\Repository\TerritoryRepositoryInterface;

class TerritoryService
{
    public function __construct(
        protected TerritoryRepositoryInterface $modelRepository
    ) {}

    public function all(): array
    {
        return $this->modelRepository->all()->toArray();
    }

    public function getById(string $id)
    {
        return $this->modelRepository->getById($id, ['island:id,name']) ?? abort(404, 'Record not found.');
    }

    public function create(array $data)
    {
        return $this->modelRepository->create($data)->load(['island:id,name']);
    }

    public function update(string $id, array $data)
    {
        return $this->modelRepository->update($id, $data)->load(['island:id,name']);
    }

    public function delete(string $id)
    {
        return $this->modelRepository->delete($id);
    }
}
