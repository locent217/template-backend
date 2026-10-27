<?php

namespace App\Modules\UserPermission\Service;

use App\Modules\UserPermission\Repository\UserPermissionRepositoryInterface;

class UserPermissionService
{
    public function __construct(
        protected UserPermissionRepositoryInterface $modelRepository
    ) {}

    public function all(): array
    {
        return $this->modelRepository->all()->toArray();
    }
}
