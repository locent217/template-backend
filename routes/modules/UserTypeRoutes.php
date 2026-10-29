<?php

use App\Http\Controllers\UserTypeController;
use Illuminate\Support\Facades\Route;

// Loaded inside the existing /api authenticated route group.
return new class {
    public function register(): void
    {
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
    }
};
