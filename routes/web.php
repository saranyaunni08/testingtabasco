<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterSettingsController;
use App\Http\Controllers\EditDeleteAuthController;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;




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

        Route::get('/rooms/{id}', [RoomController::class, 'show'])->name('rooms.show')->middleware('auth:admin');

        Route::get('/buildings/{id}', [RoomController::class, 'show'])->name('buildings.show');

        Route::get('/rooms', [RoomController::class, 'showRooms'])->name('rooms.index');


        Route::get('rooms/create/{building_id}', [RoomController::class, 'create'])->name('rooms.create');



        Route::post('admin/rooms', [RoomController::class, 'store'])->name('rooms.store');

        Route::get('rooms', [RoomController::class, 'index'])->name('rooms.index');
      
        Route::put('rooms/{room}', [RoomController::class, 'update'])->name('rooms.update');
        Route::get('/shops/{id}/edit', [RoomController::class, 'edit'])->name('shops.edit');
        Route::put('/shops/{id}', [RoomController::class, 'update'])->name('shops.update');
        // Route::delete('/shops/{id}', [RoomController::class, 'destroy'])->name('shops.destroy');
        Route::post('/admin/rooms/store', [RoomController::class, 'store'])->name('admin.rooms.store');
        Route::put('rooms/{id}/sell', [RoomController::class, 'processSell'])->name('rooms.sell');
        Route::get('/rooms/{room}/sell', [SaleController::class, 'create'])->name('sales.create');

        Route::get('/sales', [SaleController::class, 'showSales'])->name('sales.index');
        Route::get('/buildings/{building_id}/rooms', [RoomController::class, 'showRooms'])->name('buildings.rooms');

        Route::delete('sales/{id}/soft-delete', [SaleController::class, 'softDelete'])->name('sales.soft-delete');


        Route::post('/sales/store', [SaleController::class, 'store'])->name('sales.store');
        Route::post('/sales/cac-type', [SaleController::class, 'getCalculationType'])->name('sales.caltype');


        
        Route::get('/customers/{customerName}', [SaleController::class, 'showCustomer'])->name('customers.show');



        Route::get('/room-dashboard', [RoomController::class, 'dashboard'])->name('room-dashboard');

        Route::get('/room-dashboard/{building_id}', [RoomController::class, 'showBuildingRooms'])->name('building-room-dashboard');


  
        Route::get('/get-gst-percentages', [MasterSettingsController::class, 'getGstPercentages'])->name('getGstPercentages');

        Route::get('/flats/{building_id}', [RoomController::class, 'showFlats'])->name('flats.index');
        Route::get('/shops/{building_id}', [RoomController::class, 'showShops'])->name('shops.index');
        Route::get('buildings/{building_id}/kiosks', [RoomController::class, 'kiosks'])->name('kiosks.index');
        Route::get('/buildings/{building_id}/chair-spaces', [RoomController::class, 'chairSpaces'])->name('chair-spaces.index');

        Route::get('/buildingdashboard', 'App\Http\Controllers\BuildingController@index')->name('buildingdashboard');
        Route::post('/installments/{sale}/mark-paid', [SaleController::class, 'markInstallmentPaid'])->name('installments.markPaid');

        
        
        Route::get('/rooms/difference/{building_id}', [RoomController::class, 'difference'])
        ->name('flats.difference');
        
        Route::get('/rooms/difference/shops/{building_id}', [RoomController::class, 'shopsDifference'])->name('shops.difference');


        Route::get('/buildings/{building_id}/kiosks', [RoomController::class, 'showKiosks'])->name('kiosks.index');


        Route::get('/buildings/{building_id}/chair-spaces', [RoomController::class, 'showChairSpaces'])->name('chair-spaces.index');


        Route::get('/buildings/{building_id}/table-spaces', [RoomController::class, 'showTableSpaces'])->name('table-spaces.index');


        Route::put('/installments/markAsPaid', [SaleController::class, 'markAsPaid'])->name('installments.markAsPaid');

        Route::put('/installments/{id}/markAsPaid', [SaleController::class, 'markAsPaid'])
        ->name('installments.markAsPaid');
        Route::put('/customers/{customer}/installments/{installment}/markAsPaid', [SaleController::class, 'markAsPaid'])->name('installments.markAsPaid');
        Route::put('/installments/markMultipleAsPaid', [SaleController::class, 'markMultipleAsPaid'])->name('installments.markMultipleAsPaid');

        Route::put('/customers/{id}', [SaleController::class, 'update'])->name('customers.update');

        Route::get('customers/total-customers', [RoomController::class, 'totalCustomers'])->name('customers.total_customers');

        Route::get('/customers/{customerName}/download', [SaleController::class, 'downloadCustomerDetails'])->name('customers.download');
        Route::get('/customers/download-pdf/{customerName}', [SaleController::class, 'downloadPdf'])->name('customers.downloadPdf');

        Route::get('/installments/{id}/downloadPdf', [SaleController::class, 'downloadInstallmentPdf'])->name('installments.downloadPdf');
        Route::get('/test-pdf', function () {
            return view('test_pdf');
        });
        
        Route::post('/sales/cancel', [SaleController::class, 'cancelSale'])->name('sales.cancel');
        Route::get('/sales/cancelled', [SaleController::class, 'listCancelledSales'])->name('sales.cancelled');
        Route::get('/sales/cancelled/{id}', [SaleController::class, 'viewCancelledSaleDetails'])->name('sales.cancelled_details');

        Route::get('/edit-delete-login', [EditDeleteAuthController::class, 'showLogin'])->name('edit_delete_auth.show_login');
        Route::post('/edit-delete-login', [EditDeleteAuthController::class, 'authenticate'])->name('edit_delete_auth.authenticate');
        Route::post('/edit-delete-logout', [EditDeleteAuthController::class, 'logout'])->name('edit_delete_auth.logout');

        Route::post('/auth', [EditDeleteAuthController::class, 'authenticate'])->name('auth');
        
        Route::get('/rooms/{roomId}/{buildingId}/edit', [EditDeleteAuthController::class, 'showEditPage'])->name('rooms.edit');
        // Define route for handling POST requests
        Route::post('/rooms/{roomId}/{buildingId}/edit', [EditDeleteAuthController::class, 'authenticate'])->name('rooms.authenticate');

        Route::post('/edit-delete-logout', [EditDeleteAuthController::class, 'logout'])->name('edit_delete_auth.logout');

      
        Route::delete('/rooms/{roomId}/{buildingId}', [EditDeleteAuthController::class, 'deleteRoom'])->name('rooms.destroy');
        Route::delete('/rooms/{roomId}/{buildingId}/deleteFlat', [EditDeleteAuthController::class, 'deleteFlat'])->name('rooms.destroy.flat');
        Route::delete('/rooms/{roomId}/{buildingId}/deleteShops', [EditDeleteAuthController::class, 'deleteShops'])->name('rooms.destroy.Shops');
        Route::delete('/rooms/{roomId}/{buildingId}/deleteShops', [EditDeleteAuthController::class, 'deleteKiosk'])->name('rooms.destroy.Kiosk');
        Route::delete('/rooms/{roomId}/{buildingId}/deleteTableSpace', [EditDeleteAuthController::class, 'deleteTableSpace'])->name('rooms.destroy.deleteTableSpace');
        Route::delete('/rooms/{roomId}/{buildingId}/deleteChairSpace', [EditDeleteAuthController::class, 'deleteChairSpace'])->name('rooms.destroy.chairspace');

    });
});