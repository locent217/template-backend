<?php

namespace App\Providers\RepositoryServiceProviders;

use Illuminate\Support\ServiceProvider;
use App\Modules\Island\Repository\IslandRepositoryInterface;
use App\Modules\Island\Repository\Eloquent\IslandRepository;

class IslandRepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            IslandRepositoryInterface::class,
            IslandRepository::class
        );
    }
}
