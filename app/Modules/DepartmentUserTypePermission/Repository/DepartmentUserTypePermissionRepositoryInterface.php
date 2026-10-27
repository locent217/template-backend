<?php

namespace App\Modules\DepartmentUserTypePermission\Repository;

use Illuminate\Support\Collection;

interface DepartmentUserTypePermissionRepositoryInterface
{
    public function all(): Collection;
}
