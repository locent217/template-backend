<?php

use App\Http\Controllers\DepartmentController;
use Illuminate\Support\Facades\Route;

// Loaded inside the existing /api authenticated route group.
return new class {
    public function register(): void
    {
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
    }
};
