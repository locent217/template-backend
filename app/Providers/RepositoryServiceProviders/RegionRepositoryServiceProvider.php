<?php

namespace App\Providers\RepositoryServiceProviders;

use Illuminate\Support\ServiceProvider;
use App\Modules\Region\Repository\RegionRepositoryInterface;
use App\Modules\Region\Repository\Eloquent\RegionRepository;

class RegionRepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            RegionRepositoryInterface::class,
            RegionRepository::class
        );
    }
}
