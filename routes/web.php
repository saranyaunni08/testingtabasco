<?php

use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return redirect('login');
});

Route::prefix('admin')->group(function () {
    Route::name('admin.')->group(function () {
        Route::controller(AdminController::class)->group(function () {
            Route::get('/', 'index')->name('dashboard');
            Route::get('/buildingpage', 'buildingpage')->name('building');
            Route::get('/addbuilding', 'addbuilding')->name('addbuilding');
            Route::post('/buildingstore', 'buildingstore')->name('addbuilding.store');
            Route::delete('/building/{id}', 'destroy')->name('building.delete');

        });
    });
});
