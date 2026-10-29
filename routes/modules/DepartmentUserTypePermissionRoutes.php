<?php

use App\Http\Controllers\DepartmentUserTypePermissionController;
use Illuminate\Support\Facades\Route;

// Loaded inside the existing /api authenticated route group.
return new class {
    public function register(): void
    {
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
    }
};
