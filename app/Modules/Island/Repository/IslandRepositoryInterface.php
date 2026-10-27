<?php

namespace App\Modules\Island\Repository;

use Illuminate\Support\Collection;

interface IslandRepositoryInterface
{
    public function all(): Collection;
}
