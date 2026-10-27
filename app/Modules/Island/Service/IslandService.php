<?php

namespace App\Modules\Island\Service;

use App\Modules\Island\Repository\IslandRepositoryInterface;

class IslandService
{
    public function __construct(
        protected IslandRepositoryInterface $modelRepository
    ) {}

    public function all(): array
    {
        return $this->modelRepository->all()->toArray();
    }
}
