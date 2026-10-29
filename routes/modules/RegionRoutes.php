<?php

use App\Http\Controllers\RegionController;
use Illuminate\Support\Facades\Route;

// Loaded inside the existing /api authenticated route group.
return new class {
    public function register(): void
    {
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
    }
};
