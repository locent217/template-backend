<?php

namespace App\Modules\DepartmentUserTypePermission\Service;

use App\Modules\DepartmentUserTypePermission\Repository\DepartmentUserTypePermissionRepositoryInterface;

class DepartmentUserTypePermissionService
{
    public function __construct(
        protected DepartmentUserTypePermissionRepositoryInterface $modelRepository
    ) {}

    public function all(): array
    {
        return $this->modelRepository->all()->toArray();
    }
}
