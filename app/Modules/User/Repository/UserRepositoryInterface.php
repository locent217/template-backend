<?php

namespace App\Modules\User\Repository;

use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    public function all(): Collection;
}
