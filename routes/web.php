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
            
            Route::resource('rooms', RoomController::class);

    });
});


Route::get('admin/rooms/create', [RoomController::class, 'create'])->name('rooms.create');
Route::post('/admin/rooms', [RoomController::class, 'store'])->name('rooms.store');
Route::resource('rooms', RoomController::class);
Route::delete('/rooms/{id}', 'App\Http\Controllers\RoomController@destroy')->name('rooms.destroy');


}); 