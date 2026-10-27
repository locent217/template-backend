<?php

namespace App\Providers\RepositoryServiceProviders;

use Illuminate\Support\ServiceProvider;
use App\Modules\Branch\Repository\BranchRepositoryInterface;
use App\Modules\Branch\Repository\Eloquent\BranchRepository;

class BranchRepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            BranchRepositoryInterface::class,
            BranchRepository::class
        );
    }
}
