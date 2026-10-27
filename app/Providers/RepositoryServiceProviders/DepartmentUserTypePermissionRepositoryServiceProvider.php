<?php

namespace App\Providers\RepositoryServiceProviders;

use Illuminate\Support\ServiceProvider;
use App\Modules\DepartmentUserTypePermission\Repository\DepartmentUserTypePermissionRepositoryInterface;
use App\Modules\DepartmentUserTypePermission\Repository\Eloquent\DepartmentUserTypePermissionRepository;

class DepartmentUserTypePermissionRepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            DepartmentUserTypePermissionRepositoryInterface::class,
            DepartmentUserTypePermissionRepository::class
        );
    }
}
