<?php

namespace App\Providers\RepositoryServiceProviders;

use Illuminate\Support\ServiceProvider;
use App\Modules\Module\Repository\ModuleRepositoryInterface;
use App\Modules\Module\Repository\Eloquent\ModuleRepository;

class ModuleRepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            ModuleRepositoryInterface::class,
            ModuleRepository::class
        );
    }
}
