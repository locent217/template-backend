<?php

namespace App\Modules\Module\Repository;

use Illuminate\Support\Collection;

interface ModuleRepositoryInterface
{
    public function getCategories(): Collection;
    public function all(): Collection;
}
