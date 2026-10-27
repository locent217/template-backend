<?php

namespace App\Modules\UserType\Repository;

use Illuminate\Support\Collection;

interface UserTypeRepositoryInterface
{
    public function all(): Collection;
}
