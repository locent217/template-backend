<?php

namespace App\Providers\RepositoryServiceProviders;

use App\Modules\User\Repository\Eloquent\UserRepository;
use App\Modules\User\Repository\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class UserRepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );
    }
}
