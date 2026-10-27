<?php

use Illuminate\Support\Facades\Route;
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

Route::prefix('api')->group(function () {

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('guest');

    Route::middleware('auth:sanctum')->group(function () {

        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);

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

        Route::prefix('departments')->group(function () {

            Route::get('/', [DepartmentController::class, 'index'])
                ->middleware('permission:departments.view');

            Route::get('/{id}', [DepartmentController::class, 'getById'])
                ->middleware('permission:departments.view')
                ->whereUuid('id');

            Route::post('/add', [DepartmentController::class, 'add'])
                ->middleware('permission:departments.create');

            Route::put('/update/{id}', [DepartmentController::class, 'update'])
                ->middleware('permission:departments.update')
                ->whereUuid('id');

            Route::delete('/delete/{id}', [DepartmentController::class, 'destroy'])
                ->middleware('permission:departments.delete')
                ->whereUuid('id');
        });

        Route::prefix('user-types')->group(function () {
            Route::get('/', [UserTypeController::class, 'index'])
                ->middleware('permission:user_types.view');

            Route::get('/{id}', [UserTypeController::class, 'getById'])
                ->middleware('permission:user_types.view')
                ->whereUuid('id');

            Route::post('/add', [UserTypeController::class, 'add'])
                ->middleware('permission:user_types.create');

            Route::put('/update/{id}', [UserTypeController::class, 'update'])
                ->middleware('permission:user_types.update')
                ->whereUuid('id');

            Route::delete('/delete/{id}', [UserTypeController::class, 'destroy'])
                ->middleware('permission:user_types.delete')
                ->whereUuid('id');

        });

        Route::prefix('branches')->group(function () {
            Route::get('/', [BranchController::class, 'index'])
                ->middleware('permission:branches.view');

            Route::get('/{id}', [BranchController::class, 'getById'])
                ->middleware('permission:branches.view')
                ->whereUuid('id');

            Route::post('/add', [BranchController::class, 'add'])
                ->middleware('permission:branches.create');

            Route::put('/update/{id}', [BranchController::class, 'update'])
                ->middleware('permission:branches.update')
                ->whereUuid('id');

            Route::delete('/delete/{id}', [BranchController::class, 'destroy'])
                ->middleware('permission:branches.delete')
                ->whereUuid('id');

        });

        Route::prefix('regions')->group(function () {
            Route::get('/', [RegionController::class, 'index'])
                ->middleware('permission:regions.view');

            Route::get('/{id}', [RegionController::class, 'getById'])
                ->middleware('permission:regions.view')
                ->whereUuid('id');

            Route::post('/add', [RegionController::class, 'add'])
                ->middleware('permission:regions.create');

            Route::put('/update/{id}', [RegionController::class, 'update'])
                ->middleware('permission:regions.update')
                ->whereUuid('id');

            Route::delete('/delete/{id}', [RegionController::class, 'destroy'])
                ->middleware('permission:regions.delete')
                ->whereUuid('id');

        });

        Route::prefix('territories')->group(function () {
            Route::get('/', [TerritoryController::class, 'index'])
                ->middleware('permission:territories.view');

            Route::get('/{id}', [TerritoryController::class, 'getById'])
                ->middleware('permission:territories.view')
                ->whereUuid('id');

            Route::post('/add', [TerritoryController::class, 'add'])
                ->middleware('permission:territories.create');

            Route::put('/update/{id}', [TerritoryController::class, 'update'])
                ->middleware('permission:territories.update')
                ->whereUuid('id');

            Route::delete('/delete/{id}', [TerritoryController::class, 'destroy'])
                ->middleware('permission:territories.delete')
                ->whereUuid('id');

        });

        Route::prefix('islands')->group(function () {
            Route::get('/', [IslandController::class, 'index'])
                ->middleware('permission:islands.view');

            Route::get('/{id}', [IslandController::class, 'getById'])
                ->middleware('permission:islands.view')
                ->whereUuid('id');

            Route::post('/add', [IslandController::class, 'add'])
                ->middleware('permission:islands.create');

            Route::put('/update/{id}', [IslandController::class, 'update'])
                ->middleware('permission:islands.update')
                ->whereUuid('id');

            Route::delete('/delete/{id}', [IslandController::class, 'destroy'])
                ->middleware('permission:islands.delete')
                ->whereUuid('id');

        });

        Route::prefix('modules')->group(function () {
            // Static route first: GET /api/modules/module-categories.
            Route::get('/module-categories', [ModuleController::class, 'categories'])
                ->middleware('permission:modules.view');

            Route::get('/', [ModuleController::class, 'index'])
                ->middleware('permission:modules.view');

            Route::get('/{id}', [ModuleController::class, 'getById'])
                ->middleware('permission:modules.view')
                ->whereUuid('id');

            Route::post('/add', [ModuleController::class, 'add'])
                ->middleware('permission:modules.create')
                ->middleware('super_admin');

            Route::put('/update/{id}', [ModuleController::class, 'update'])
                ->middleware('permission:modules.update')
                ->middleware('super_admin')
                ->whereUuid('id');

            Route::delete('/delete/{id}', [ModuleController::class, 'destroy'])
                ->middleware('permission:modules.delete')
                ->middleware('super_admin')
                ->whereUuid('id');

        });

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

        Route::prefix('department-user-type-permissions')->group(function () {
            Route::get('/', [DepartmentUserTypePermissionController::class, 'index'])
                ->middleware('permission:department_user_type_permissions.view');

            Route::get('/{id}', [DepartmentUserTypePermissionController::class, 'getById'])
                ->middleware('permission:department_user_type_permissions.view')
                ->whereUuid('id');

            Route::post('/add', [DepartmentUserTypePermissionController::class, 'add'])
                ->middleware('permission:department_user_type_permissions.create')
                ->middleware('super_admin');

            Route::put('/update/{id}', [DepartmentUserTypePermissionController::class, 'update'])
                ->middleware('permission:department_user_type_permissions.update')
                ->middleware('super_admin')
                ->whereUuid('id');

            Route::delete('/delete/{id}', [DepartmentUserTypePermissionController::class, 'destroy'])
                ->middleware('permission:department_user_type_permissions.delete')
                ->middleware('super_admin')
                ->whereUuid('id');

        });

    });
});
