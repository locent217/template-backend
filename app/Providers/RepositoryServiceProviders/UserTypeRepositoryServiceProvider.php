<?php

namespace App\Providers\RepositoryServiceProviders;

use Illuminate\Support\ServiceProvider;
use App\Modules\UserType\Repository\UserTypeRepositoryInterface;
use App\Modules\UserType\Repository\Eloquent\UserTypeRepository;

class UserTypeRepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            UserTypeRepositoryInterface::class,
            UserTypeRepository::class
        );
    }
}
