<?php

use App\Http\Controllers\IslandController;
use Illuminate\Support\Facades\Route;

// Loaded inside the existing /api authenticated route group.
return new class {
    public function register(): void
    {
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
    }
};
