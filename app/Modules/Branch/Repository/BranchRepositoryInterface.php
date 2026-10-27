<?php

namespace App\Modules\Branch\Repository;

use Illuminate\Support\Collection;

interface BranchRepositoryInterface
{
    public function all(): Collection;
}
