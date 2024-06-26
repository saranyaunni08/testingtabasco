<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterSettingsController;

Route::get('/', function () {
    return redirect('login');
});

Route::controller(AuthController::class)->group(function () {
    Route::get('login', 'login')->name('login');
    Route::post('do-login', 'doLogin')->name('dologin');
    Route::get('forgot-password', 'forgotPassword')->name('pswreset');
    Route::get('forgot-password', 'showForgotPasswordForm')->name('password.request');
    Route::post('forgot-password', 'forgotPassword')->name('password.email');
    Route::get('reset-password/{token}', 'showResetPasswordForm')->name('password.reset');
    Route::post('reset-password', 'resetPassword')->name('password.update');
    Route::get('reset-password', 'resetPassword')->name('password.update');
    Route::post('/forgot-password', 'forgotPassword')->name('forgot_password');
    Route::post('/forgot_password', 'sendResetLinkEmail')->name('forgot_password');
    Route::get('/forgot_password', 'sendResetLinkEmail')->name('forgot_password');
});

// Admin routes
Route::middleware('auth:admin')->prefix('admin')->group(function () {
    Route::name('admin.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('rooms', RoomController::class);
        Route::get('/buildings', [BuildingController::class, 'index'])->name('building');
        Route::get('/add-building', [BuildingController::class, 'create'])->name('addbuilding');
        Route::get('/edit-building/{id}', [BuildingController::class, 'edit'])->name('building.editbuilding');
        Route::post('/update-building/{id}', [BuildingController::class, 'update'])->name('building.update');
        Route::post('/buildingstore', [BuildingController::class, 'store'])->name('addbuilding.store');

        Route::get('/building', [BuildingController::class, 'index'])->name('building.index');


        Route::delete('/buildings/{id}', [BuildingController::class, 'destroy'])->name('building.delete');

        Route::get('/buildings/{id}', [BuildingController::class, 'show'])->name('buildings.show');

        Route::get('/buildings/{building_id}/rooms', [RoomController::class, 'showRooms'])->name('buildings.rooms');
        Route::resource('rooms', RoomController::class)->except(['show']);

        // Route::get('/buildings/{buildingId}/rooms', [RoomController::class, 'show'])->name('rooms.show');
        Route::get('/rooms/{id}', [RoomController::class, 'show'])->name('rooms.show')->middleware('auth:admin');

        Route::get('/buildings/{id}', [RoomController::class, 'show'])->name('buildings.show');

        Route::get('/rooms', [RoomController::class, 'showRooms'])->name('rooms.index');


        Route::get('/buildings/{building_id}/rooms/create', [RoomController::class, 'create'])->name('rooms.create');

        Route::post('admin/rooms', [RoomController::class, 'store'])->name('admin.rooms.store');

        Route::get('rooms', [RoomController::class, 'index'])->name('rooms.index');
        Route::delete('/buildings/{building_id}/rooms/{room_id}', 'RoomController@destroy')->name('rooms.destroy');



        // Route::delete('/rooms/{id}', [RoomController::class, 'destroy'])->name('rooms.destroy');
        Route::delete('buildings/{building_id}/rooms/{room_id}', [RoomController::class, 'destroy'])->name('rooms.destroy');


        Route::get('rooms/{room}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
        Route::put('rooms/{room}', [RoomController::class, 'update'])->name('rooms.update');
        Route::get('/shops/{id}/edit', [RoomController::class, 'edit'])->name('shops.edit');
        Route::put('/shops/{id}', [RoomController::class, 'update'])->name('shops.update');
        Route::delete('/shops/{id}', [RoomController::class, 'destroy'])->name('shops.destroy');
        Route::post('/admin/rooms/store', [RoomController::class, 'store'])->name('admin.rooms.store');
        Route::put('rooms/{id}/sell', [RoomController::class, 'processSell'])->name('rooms.sell');
        Route::get('/rooms/{room}/sell', [SaleController::class, 'create'])->name('sales.create');

        Route::get('/sales', [SaleController::class, 'showSales'])->name('sales.index');
        Route::get('/buildings/{building_id}/rooms', [RoomController::class, 'showRooms'])->name('buildings.rooms');

        Route::delete('sales/{id}/soft-delete', [SaleController::class, 'softDelete'])->name('sales.soft-delete');


        Route::post('/sales/store', [SaleController::class, 'store'])->name('sales.store');
        Route::post('/sales/cac-type', [SaleController::class, 'getCalculationType'])->name('sales.caltype');

        Route::get('/customers', [SaleController::class, 'index'])->name('customers.index');

        Route::get('/customers/{customerName}', [SaleController::class, 'showCustomer'])->name('customers.show');



        Route::get('/room-dashboard', [RoomController::class, 'dashboard'])->name('room-dashboard');

        Route::get('/room-dashboard/{building_id}', [RoomController::class, 'showBuildingRooms'])->name('building-room-dashboard');


        Route::get('/masters', [MasterSettingsController::class, 'index'])->name('masters.index');
        Route::post('/masters', [MasterSettingsController::class, 'store'])->name('masters.store');
        Route::get('/get-gst-percentages', [MasterSettingsController::class, 'getGstPercentages'])->name('getGstPercentages');


    });
});



