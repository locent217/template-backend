<?php

namespace App\Modules\Territory\Service;

use App\Modules\Territory\Repository\TerritoryRepositoryInterface;

class TerritoryService
{
    public function __construct(
        protected TerritoryRepositoryInterface $modelRepository
    ) {}

    public function all(): array
    {
        return $this->modelRepository->all()->toArray();
    }
}
