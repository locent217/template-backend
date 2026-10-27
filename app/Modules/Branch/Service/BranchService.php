<?php

namespace App\Modules\Branch\Service;

use App\Modules\Branch\Repository\BranchRepositoryInterface;

class BranchService
{
    public function __construct(
        protected BranchRepositoryInterface $modelRepository
    ) {}

    public function all(): array
    {
        return $this->modelRepository->all()->toArray();
    }

    public function getById(string $id)
    {
        return $this->modelRepository->getById($id);
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
