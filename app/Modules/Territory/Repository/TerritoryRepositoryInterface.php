<?php

namespace App\Modules\Territory\Repository;

use Illuminate\Support\Collection;

interface TerritoryRepositoryInterface
{
    public function all(): Collection;
}
