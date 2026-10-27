<?php

namespace App\Providers\RepositoryServiceProviders;

use Illuminate\Support\ServiceProvider;
use App\Modules\Department\Repository\DepartmentRepositoryInterface;
use App\Modules\Department\Repository\Eloquent\DepartmentRepository;

class DepartmentRepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            DepartmentRepositoryInterface::class,
            DepartmentRepository::class
        );
    }
}
