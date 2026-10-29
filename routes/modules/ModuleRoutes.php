<?php

use App\Http\Controllers\ModuleController;
use Illuminate\Support\Facades\Route;

// Loaded inside the existing /api authenticated route group.
return new class {
    public function register(): void
    {
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
    }
};
