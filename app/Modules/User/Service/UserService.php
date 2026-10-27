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
        return $this->repository->find($id);
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function update(string $id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function delete(string $id)
    {
        return $this->repository->delete($id);
    }

}
