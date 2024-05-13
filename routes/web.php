<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;

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
      });
      
      Route::get('rooms/create', [RoomController::class, 'create'])->name('rooms.create');
      Route::resource('rooms', RoomController::class);
      Route::delete('/rooms/{id}', 'App\Http\Controllers\RoomController@destroy')->name('rooms.destroy');
      Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');

      Route::get('rooms/{room}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
      Route::put('rooms/{room}', [RoomController::class, 'update'])->name('rooms.update');

      Route::get('/shops/{id}/edit', 'RoomController@edit')->name('shops.edit');
      Route::put('/shops/{id}', 'RoomController@update')->name('shops.update');
      Route::delete('/shops/{id}', 'RoomController@destroy')->name('shops.destroy');

      Route::get('/buildings', 'buildingpage')->name('building');
      Route::get('/add-building', 'addbuilding')->name('addbuilding');
      Route::get('/edit-building/{id}', 'editbuilding')->name('building.editbuilding');
      Route::post('/update-building/{id}', 'updatebuilding')->name('building.update');
      Route::post('/buildingstore', 'buildingstore')->name('addbuilding.store');
      Route::delete('/buildings/{id}', 'destroy')->name('building.delete');
    });
});
