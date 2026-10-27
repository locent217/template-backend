<?php

namespace App\Providers\RepositoryServiceProviders;

use Illuminate\Support\ServiceProvider;
use App\Modules\Territory\Repository\TerritoryRepositoryInterface;
use App\Modules\Territory\Repository\Eloquent\TerritoryRepository;

class TerritoryRepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            TerritoryRepositoryInterface::class,
            TerritoryRepository::class
        );
    }
}
