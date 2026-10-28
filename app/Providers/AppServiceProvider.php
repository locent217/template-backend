<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Permission;
use App\Models\User;
use App\Modules\Permission\Service\PermissionService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(\App\Modules\Notification\Repository\NotificationRepositoryInterface::class, \App\Modules\Notification\Repository\Eloquent\NotificationRepository::class);
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /*
         * Register audit observers automatically for eligible Eloquent models.
         * Disabling auditing skips only this block, not other boot logic.
         */
        if (config('audit.enabled', true)) {
            foreach (\App\Support\Audit\AuditableModelDiscovery::discover() as $model) {
                $model::observe(\App\Observers\AuditedModelObserver::class);
            }
        }
    }
}
