<?php

namespace App\Modules\Module\Service;

use App\Modules\Module\Repository\ModuleRepositoryInterface;

class ModuleService
{
    public function __construct(
        protected ModuleRepositoryInterface $modelRepository
    ) {}

    public function all(): array
    {
        return $this->modelRepository->all()->toArray();
    }
}
