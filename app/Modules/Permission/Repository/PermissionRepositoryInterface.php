<?php

namespace App\Modules\Permission\Repository;

use Illuminate\Support\Collection;

interface PermissionRepositoryInterface
{
    public function all(): Collection;
}
