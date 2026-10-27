<?php

namespace App\Providers\RepositoryServiceProviders;

use Illuminate\Support\ServiceProvider;
use App\Modules\Permission\Repository\PermissionRepositoryInterface;
use App\Modules\Permission\Repository\Eloquent\PermissionRepository;

class PermissionRepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            PermissionRepositoryInterface::class,
            PermissionRepository::class
        );
    }
}
