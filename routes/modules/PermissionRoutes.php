<?php

use App\Http\Controllers\PermissionController;
use Illuminate\Support\Facades\Route;

// Loaded inside the existing /api authenticated route group.
return new class {
    public function register(): void
    {
        Route::prefix('permissions')->group(function () {
            Route::get('/', [PermissionController::class, 'index'])
                ->middleware('permission:permissions.view');

            Route::get('/{id}', [PermissionController::class, 'getById'])
                ->middleware('permission:permissions.view')
                ->whereUuid('id');

            Route::post('/add', [PermissionController::class, 'add'])
                ->middleware('permission:permissions.create')
                ->middleware('super_admin');

            Route::put('/update/{id}', [PermissionController::class, 'update'])
                ->middleware('permission:permissions.update')
                ->middleware('super_admin')
                ->whereUuid('id');

            Route::delete('/delete/{id}', [PermissionController::class, 'destroy'])
                ->middleware('permission:permissions.delete')
                ->middleware('super_admin')
                ->whereUuid('id');

        });
    }
};
