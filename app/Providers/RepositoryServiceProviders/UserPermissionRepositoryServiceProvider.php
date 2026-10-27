<?php

namespace App\Providers\RepositoryServiceProviders;

use Illuminate\Support\ServiceProvider;
use App\Modules\UserPermission\Repository\UserPermissionRepositoryInterface;
use App\Modules\UserPermission\Repository\Eloquent\UserPermissionRepository;

class UserPermissionRepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            UserPermissionRepositoryInterface::class,
            UserPermissionRepository::class
        );
    }
}
