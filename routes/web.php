<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BuildingController; // Added BuildingController
use App\Http\Controllers\SalesController;


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
            Route::get('/buildings', [BuildingController::class, 'index'])->name('building'); 
            Route::get('/add-building', [BuildingController::class, 'create'])->name('addbuilding'); 
            Route::get('/edit-building/{id}', [BuildingController::class, 'edit'])->name('building.editbuilding'); 
            Route::post('/update-building/{id}', [BuildingController::class, 'update'])->name('building.update'); 
            Route::post('/buildingstore', [BuildingController::class, 'store'])->name('addbuilding.store'); 
            Route::delete('/buildings/{id}', [BuildingController::class, 'destroy'])->name('building.delete'); 
            Route::get('/buildings/{id}', [BuildingController::class, 'show'])->name('buildings.show');
            Route::get('/admin/building', [BuildingController::class, 'index'])->name('admin.building');
            Route::post('/sales/store', [SalesController::class, 'store'])->name('sales.store');




        });

        Route::resource('rooms', RoomController::class);

        Route::get('rooms', [RoomController::class, 'index'])->name('rooms.index');

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
        
            Route::get('sales/create', [SalesController::class, 'create'])->name('sales.create');

            Route::get('/admin/sales/create/{roomId}', [SalesController::class, 'viewSalesForm'])->name('admin.sales.create');

            Route::get('/admin/sales/{roomId}', [SalesController::class, 'viewSalesForm'])->name('admin.sales.form');

            Route::post('/admin/sales/store', [SalesController::class, 'store'])->name('admin.sales.store');

    });

});
