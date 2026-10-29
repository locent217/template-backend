<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;

// Stateful SPA authorization for private notification channels.
// Uses the existing Sanctum session cookie (not an exposed API token).
Broadcast::routes(['middleware' => ['web', 'auth:sanctum']]);
require __DIR__.'/channels.php';
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserPermissionController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\UserTypeController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\TerritoryController;
use App\Http\Controllers\IslandController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\DepartmentUserTypePermissionController;

Route::prefix('api')->middleware(\App\Http\Middleware\AuditHttpRequest::class)->group(function () {

    // Public account recovery; use generic responses to avoid exposing registered addresses.
    Route::post('/account/forgot-username', [\App\Http\Controllers\AccountRecoveryController::class, 'forgotUsername'])->middleware('throttle:3,1');
    Route::post('/account/forgot-password', [\App\Http\Controllers\AccountRecoveryController::class, 'forgotPassword'])->middleware('throttle:3,1');
    Route::post('/account/reset-password', [\App\Http\Controllers\AccountRecoveryController::class, 'resetPassword'])->middleware('throttle:5,1');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('guest');

    Route::middleware(['auth:sanctum', \App\Http\Middleware\EnsureFirstUseCompleted::class])->group(function () {

        // Restricted read-only audit log endpoints. OR: Super Admin user type or IT department.
        Route::prefix('audit-logs')->middleware(\App\Http\Middleware\AuthorizeAuditLogAccess::class)->group(function () {
            Route::get('/', [\App\Http\Controllers\AuditLogController::class, 'index']);
            Route::get('/actors', [\App\Http\Controllers\AuditLogController::class, 'actors']);
            Route::get('/{id}', [\App\Http\Controllers\AuditLogController::class, 'show'])->whereUuid('id');
        });

        Route::prefix('notifications')->group(function () {
            Route::get('/', [\App\Http\Controllers\NotificationController::class, 'index']);
            Route::patch('/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markRead'])->whereUuid('id');
            Route::patch('/{id}/unread', [\App\Http\Controllers\NotificationController::class, 'markUnread'])->whereUuid('id');
        });

        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/account/change-password', [\App\Http\Controllers\AccountRecoveryController::class, 'changePassword'])->middleware('throttle:5,1');
        Route::post('/account/credentials', [AuthController::class, 'changeFirstUseCredentials'])->middleware('throttle:5,1');
        Route::post('/logout', [AuthController::class, 'logout']);

        // Load each module route class in a stable order. Each file owns its own
        // prefix, permissions and routes, avoiding shared web.php Git conflicts.
        foreach (glob(__DIR__.'/modules/*Routes.php') ?: [] as $routeFile) {
            (require $routeFile)->register();
        }

    });
});
