<?php

use App\Http\Controllers\TerritoryController;
use Illuminate\Support\Facades\Route;

// Loaded inside the existing /api authenticated route group.
return new class {
    public function register(): void
    {
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
    }
};
