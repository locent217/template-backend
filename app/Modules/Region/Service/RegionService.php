<?php

namespace App\Modules\Region\Service;

use App\Modules\Region\Repository\RegionRepositoryInterface;

class RegionService
{
    public function __construct(
        protected RegionRepositoryInterface $modelRepository
    ) {}

    public function all(): array
    {
        return $this->modelRepository->all()->toArray();
    }
}
