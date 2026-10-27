<?php

namespace App\Modules\Island\Service;

use App\Modules\Island\Repository\IslandRepositoryInterface;

class IslandService
{
    public function __construct(
        protected IslandRepositoryInterface $modelRepository
    ) {}

    public function all(): array
    {
        return $this->modelRepository->all()->toArray();
    }

    public function getById(string $id)
    {
        return $this->modelRepository->getById($id) ?? abort(404, 'Record not found.');
    }

    public function create(array $data)
    {
        return $this->modelRepository->create($data);
    }

    public function update(string $id, array $data)
    {
        return $this->modelRepository->update($id, $data);
    }

    public function delete(string $id)
    {
        return $this->modelRepository->delete($id);
    }
}
