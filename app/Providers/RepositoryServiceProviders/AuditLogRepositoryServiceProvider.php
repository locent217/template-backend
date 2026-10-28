<?php
namespace App\Providers\RepositoryServiceProviders;

use App\Modules\AuditLog\Repository\AuditLogRepositoryInterface;
use App\Modules\AuditLog\Repository\Eloquent\AuditLogRepository;
use Illuminate\Support\ServiceProvider;

class AuditLogRepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AuditLogRepositoryInterface::class, AuditLogRepository::class);
    }
}
