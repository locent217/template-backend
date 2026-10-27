<?php

namespace App\Modules\UserPermission\Repository;

use Illuminate\Support\Collection;

interface UserPermissionRepositoryInterface
{
    public function all(): Collection;
}
