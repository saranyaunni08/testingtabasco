<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

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
            Route::get('/building', 'show')->name('building');
            Route::get('/sales', 'view')->name('sales');
        });
    });
});

