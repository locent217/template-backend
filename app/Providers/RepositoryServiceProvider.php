<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Keep your existing base repository binding here,
        // if it is required by your application.

        $providers = File::glob(
            app_path('Providers/RepositoryServiceProviders/*RepositoryServiceProvider.php')
        );

        foreach ($providers as $file) {
            $className = pathinfo($file, PATHINFO_FILENAME);

            $provider = 'App\\Providers\\RepositoryServiceProviders\\' . $className;

            if (class_exists($provider)) {
                $this->app->register($provider);
            }
        }
    }
}
