<?php

namespace App\Modules\Region\Repository;

use Illuminate\Support\Collection;

interface RegionRepositoryInterface
{
    public function all(): Collection;
}
