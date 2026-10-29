<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\UserPermissionController;
use Illuminate\Support\Facades\Route;

// Loaded inside the existing /api authenticated route group.
return new class {
    public function register(): void
    {
        Route::prefix('users')->group(function () {

            Route::get('/', [UserController::class, 'index'])
                ->middleware('permission:users.view');

            Route::get('/{id}', [UserController::class, 'show'])
                ->middleware('permission:users.view')
                ->whereUuid('id');

            Route::post('/add', [UserController::class, 'add'])
                ->middleware('permission:users.create');

            Route::put('/update/{id}', [UserController::class, 'update'])
                ->middleware('permission:users.update')
                ->whereUuid('id');

            Route::delete('/delete/{id}', [UserController::class, 'destroy'])
                ->middleware('permission:users.delete')
                ->whereUuid('id');

            // Keep {user} and {permission}: controller uses Eloquent model binding.
            Route::middleware([
                'super_admin',
                'permission:users.manage_permissions',
            ])->group(function () {

                Route::get('/{user}/permissions', [UserPermissionController::class, 'index'])
                    ->whereUuid('user');

                Route::put('/{user}/permissions/{permission}', [UserPermissionController::class, 'update'])
                    ->whereUuid('user')
                    ->whereUuid('permission');

                Route::delete('/{user}/permissions/{permission}', [UserPermissionController::class, 'destroy'])
                    ->whereUuid('user')
                    ->whereUuid('permission');
            });
        });
    }
};
