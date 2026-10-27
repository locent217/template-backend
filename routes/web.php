<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserPermissionController;
use App\Http\Controllers\DepartmentController;

Route::prefix('api')->group(function () {

    // Guest routes
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('guest');

    // Authenticated routes
    Route::middleware('auth:sanctum')->group(function () {

        // Authentication
        Route::get('/me', [AuthController::class, 'me']);

        Route::post('/logout', [AuthController::class, 'logout']);

        // Users
        Route::prefix('users')->group(function () {

            Route::get('/', [UserController::class, 'index'])
                ->middleware('permission:users.view');

            Route::get('/{user}', [UserController::class, 'show'])
                ->middleware('permission:users.view')
                ->whereUuid('user');

            Route::post('/add', [UserController::class, 'add'])
                ->middleware('permission:users.create');

            Route::put('/update/{user}', [UserController::class, 'update'])
                ->middleware('permission:users.update')
                ->whereUuid('user');

            Route::delete('/delete/{user}', [UserController::class, 'destroy'])
                ->middleware('permission:users.delete')
                ->whereUuid('user');

            // User Permissions - Super Admin only
            Route::middleware([
                'super_admin',
                'permission:users.manage_permissions',
            ])->group(function () {

                Route::get('/{user}/permissions', [
                    UserPermissionController::class,
                    'index',
                ])->whereUuid('user');

                Route::put('/{user}/permissions/{permission}', [
                    UserPermissionController::class,
                    'update',
                ])
                    ->whereUuid('user')
                    ->whereUuid('permission');

                Route::delete('/{user}/permissions/{permission}', [
                    UserPermissionController::class,
                    'destroy',
                ])
                    ->whereUuid('user')
                    ->whereUuid('permission');

            });

        });

        Route::prefix('departments')->group(function () {

            Route::get('/', [DepartmentController::class, 'index'])
                ->middleware('permission:departments.view');

            Route::get('/{department}', [DepartmentController::class, 'show'])
                ->middleware('permission:departments.view')
                ->whereUuid('department');

            Route::post('/add', [DepartmentController::class, 'add'])
                ->middleware('permission:departments.create');

            Route::put('/update/{department}', [DepartmentController::class, 'update'])
                ->middleware('permission:departments.update')
                ->whereUuid('department');

            Route::delete('/delete/{department}', [DepartmentController::class, 'destroy'])
                ->middleware('permission:departments.delete')
                ->whereUuid('department');

        });
    });

});
