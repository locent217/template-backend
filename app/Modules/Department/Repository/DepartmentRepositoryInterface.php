<?php

namespace App\Modules\Department\Repository;

use Illuminate\Support\Collection;

interface DepartmentRepositoryInterface
{
    public function all(): Collection;
}
