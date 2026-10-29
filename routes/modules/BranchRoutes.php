<?php

use App\Http\Controllers\BranchController;
use Illuminate\Support\Facades\Route;

// Loaded inside the existing /api authenticated route group.
return new class {
    public function register(): void
    {
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
    }
};
