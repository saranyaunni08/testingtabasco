<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BuildingController; // Added BuildingController

Route::get('/', function () {
    return redirect('login');
});

Route::controller(AuthController::class)->group(function () {
    Route::get('login', 'login')->name('login');
    Route::post('do-login', 'doLogin')->name('dologin');
    Route::get('forgot-password', 'forgotPassword')->name('pswreset');
    Route::get('logout', 'logout')->name('logout');
});

Route::prefix('admin')->group(function () {
    Route::name('admin.')->group(function () {
        Route::controller(AdminController::class)->group(function () {
            Route::get('/', 'index')->name('dashboard');
            Route::get('/sales', 'view')->name('sales');
            Route::resource('rooms', RoomController::class);
            Route::get('/buildings', [BuildingController::class, 'index'])->name('building'); // Updated route
            Route::get('/add-building', [BuildingController::class, 'create'])->name('addbuilding'); // Updated route
            Route::get('/edit-building/{id}', [BuildingController::class, 'edit'])->name('building.editbuilding'); // Updated route
            Route::post('/update-building/{id}', [BuildingController::class, 'update'])->name('building.update'); // Updated route
            Route::post('/buildingstore', [BuildingController::class, 'store'])->name('addbuilding.store'); // Updated route
            Route::delete('/buildings/{id}', [BuildingController::class, 'destroy'])->name('building.delete'); // Updated route
            Route::get('/buildings/{id}', [BuildingController::class, 'show'])->name('buildings.show');

        });

        Route::resource('rooms', RoomController::class);

        Route::delete('/admin/rooms/{id}', [RoomController::class, 'destroy'])->name('admin.rooms.destroy');

        Route::get('rooms/{room}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
        Route::put('rooms/{room}', [RoomController::class, 'update'])->name('rooms.update');

        Route::get('/shops/{id}/edit', [RoomController::class, 'edit'])->name('shops.edit');
        Route::put('/shops/{id}', [RoomController::class, 'update'])->name('shops.update');
        Route::delete('/shops/{id}', [RoomController::class, 'destroy'])->name('shops.destroy');

        Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
            Route::resource('rooms', 'RoomController')->except(['show']);
        });
      
        
            Route::resource('rooms', RoomController::class)->except(['show']);
            Route::get('rooms/{id}/sell', [RoomController::class, 'showSellForm'])->name('rooms.showSellForm');
            Route::put('rooms/{id}/sell', [RoomController::class, 'processSell'])->name('rooms.sell');
        

    });

});
