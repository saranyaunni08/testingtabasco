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
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\TotalBuildUpAreaController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\SalesReturnReportController;
use App\Http\Controllers\ExchangeReportController;
use App\Http\Controllers\CashBookController;
use App\Http\Controllers\AccountsPayableController;







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

Route::middleware('auth:admin')->prefix('admin')->group(function () {
    Route::name('admin.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
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
        // Route::get('/rooms/create/{building_id}', [RoomController::class, 'create'])->name('rooms.create');

        Route::post('admin/rooms', [RoomController::class, 'store'])->name('rooms.store');

        Route::get('rooms', [RoomController::class, 'index'])->name('rooms.index');

        Route::put('rooms/{room}', [RoomController::class, 'update'])->name('rooms.update');

        Route::get('/shops/{id}/edit', [RoomController::class, 'edit'])->name('shops.edit');

        Route::put('/shops/{id}', [RoomController::class, 'update'])->name('shops.update');

        // Route::delete('/shops/{id}', [RoomController::class, 'destroy'])->name('shops.destroy');
        Route::post('/admin/rooms/store', [RoomController::class, 'store'])->name('admin.rooms.store');
        // Route::put('rooms/{id}/sell', [RoomController::class, 'processSell'])->name('rooms.sell');
        Route::get('/rooms/{room}/sell', [SaleController::class, 'create'])->name('sales.create');

        Route::get('/buildings/{building_id}/rooms', [RoomController::class, 'showRooms'])->name('buildings.rooms');

        Route::delete('sales/{id}/soft-delete', [SaleController::class, 'softDelete'])->name('sales.soft-delete');

        Route::post('/sales/store', [SaleController::class, 'store'])->name('sales.store');

        Route::post('/sales/cac-type', [SaleController::class, 'getCalculationType'])->name('sales.caltype');

        Route::get('/customers/sales/{saleId}', [SaleController::class, 'showCustomer'])->name('customers.show');

        // Route::get('/room-dashboard', [RoomController::class, 'dashboard'])->name('room-dashboard');

        Route::get('/room-dashboard/{building_id}', [RoomController::class, 'showBuildingRooms'])->name('building-room-dashboard');

        Route::get('/get-gst-percentages', [MasterSettingsController::class, 'getGstPercentages'])->name('getGstPercentages');

        Route::get('/flats/{building_id}', [RoomController::class, 'showFlats'])->name('flats.index');

        Route::get('/shops/{building_id}', [RoomController::class, 'showShops'])->name('shops.index');

        Route::get('/buildings/{building_id}/chair-spaces', [RoomController::class, 'chairSpaces'])->name('chair-spaces.index');

        Route::get('/buildingdashboard', [BuildingController::class, 'index'])->name('buildingdashboard');


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
        Route::post('/rooms/{roomId}/{buildingId}/edit', [EditDeleteAuthController::class, 'authenticate'])->name('rooms.authenticate');

        Route::post('/edit-delete-logout', [EditDeleteAuthController::class, 'logout'])->name('edit_delete_auth.logout');


        Route::delete('/rooms/{roomId}/{buildingId}', [EditDeleteAuthController::class, 'deleteRoom'])->name('rooms.destroy');
        Route::delete('/rooms/destroy/{roomId}/{buildingId}', [EditDeleteAuthController::class, 'destroyFlat'])->name('rooms.destroy.flat');
        Route::delete('/rooms/{roomId}/{buildingId}/deleteShops', [EditDeleteAuthController::class, 'deleteShops'])->name('rooms.deleteShops');
        Route::delete('/rooms/{roomId}/{buildingId}/deleteKiosk', [EditDeleteAuthController::class, 'deleteKiosk'])->name('rooms.destroy.Kiosk');
        Route::delete('/rooms/{roomId}/{buildingId}/deleteTableSpace', [EditDeleteAuthController::class, 'deleteTableSpace'])->name('rooms.destroy.deleteTableSpace');
        Route::delete('/rooms/{roomId}/{buildingId}/deleteChairSpace', [EditDeleteAuthController::class, 'deleteChairSpace'])->name('rooms.destroy.chairspace');

        Route::get('/kiosk/difference/{buildingId}', [RoomController::class, 'kioskDifference'])->name('kiosk.difference');
        Route::get('/chair-spaces/difference/{building_id}', [RoomController::class, 'showChairSpaceDifference'])->name('chair_spaces.difference');
        Route::get('/table-space/difference/{building_id}', [RoomController::class, 'showTableSpaceDifference'])->name('table_spaces.difference');

        Route::get('/rooms/sell/{room}/{buildingId}', [RoomController::class, 'showSellForm'])->name('rooms.sell');

        //partners
        Route::get('/partners/create', [PartnerController::class, 'create'])->name('partners.create');
        Route::post('/partners', [PartnerController::class, 'store'])->name('partners.store');

        // In routes/web.php
        Route::get('/partners/cash-in-hand', [PartnerController::class, 'cashInHand'])->name('partners.cash_in_hand');

        // New route for marking as paid
        Route::put('/partners/{partner}/mark-paid', [PartnerController::class, 'markAsPaid'])->name('partners.mark_paid');


        // Route to display the form for adding a new room type
        Route::get('/room-types/create', [RoomTypeController::class, 'create'])->name('room_types.create');

        // Route to store the new room type
        Route::post('/room-types', [RoomTypeController::class, 'store'])->name('room_types.store');

        // Route to display the list of room types
        Route::get('/room-types', [RoomTypeController::class, 'index'])->name('room_types.index');

        // In web.php or your routes file

        Route::get('/custom-rooms/{building_id}', action: [RoomController::class, 'showCustomRooms'])->name('custom_rooms');

        Route::get('/rooms/other-types-difference/{building_id}', [RoomController::class, 'otherRoomTypesDifference'])->name('rooms.other_types_difference');

        // Bank Account Excel Controller 
        Route::get('/bank-account/bank_account/{building_id}', [BankAccountController::class, 'bankaccount'])->name('bankaccount.bank_account');
        Route::get('/bankaccount.banknames_bank/{building_id}', [BankAccountController::class, 'banknames'])->name('bankaccount.banknames_bank');
    
        // Bank Module Controller 

        Route::get('/banks/create', [BankController::class, 'create'])->name('banks.create');

        Route::post('/banks/store', [BankController::class, 'store'])->name('banks.store');

        Route::get('/banks/views', [BankController::class, 'views'])->name('banks.views');

        // Define a route for editing a specific bank
        Route::get('/banks/edit/{id}', [BankController::class, 'edit'])->name('banks.edit');

        Route::delete('/banks/{bank}', [BankController::class, 'destroy'])->name('banks.destroy');

        Route::put('/banks/{id}', [BankController::class, 'update'])->name('banks.update');

        // Totalbuildup area excel
        Route::get('/totalbuildupexcel.total_breakup/{building_id}', [TotalBuildUpAreaController::class, 'totalbuildup'])->name('totalbuildupexcel.total_breakup');
        Route::get('/totalbuildupexcel.apartment_breakup/{building_id}', [TotalBuildUpAreaController::class, 'index'])->name('totalbuildupexcel.apartment_breakup');
        Route::get('/totalbuildupexcel.commercial_breakup/{building_id}', [TotalBuildUpAreaController::class, 'commercialbreakup'])->name('totalbuildupexcel.commercial_breakup');
        Route::get('/totalbuildupexcel.parking_breakup/{building_id}', [TotalBuildUpAreaController::class, 'parkingbreakup'])->name('totalbuildupexcel.parking_breakup');
        Route::get('/totalbuildupexcel.summary/{building_id}', [TotalBuildUpAreaController::class, 'summary'])->name('totalbuildupexcel.summary');
        Route::get('/totalbuildupexcel.balance_summary/{building_id}', [TotalBuildUpAreaController::class, 'balancesummary'])->name('totalbuildupexcel.balance_summary');
        Route::get('/totalbuildupexcel.changes_in_expected_amount/{building_id}', [TotalBuildUpAreaController::class, 'changesinExpectedamount'])->name('totalbuildupexcel.changes_in_expected_amount');
        // Availability excel
        Route::get('/availability-report/totalavailability/{building_id}', [AvailabilityController::class, 'totalavailability'])->name('availability.totalavailability');
        Route::get('/availability.availabilityshop/{building_id}', [AvailabilityController::class, 'availabilityshop'])->name('availability.availabilityshop');
        Route::get('/availability.availabilityflat/{building_id}', [AvailabilityController::class, 'availabilityflat'])->name('availability.availabilityflat');
        Route::get('/availability.availabilityparking/{building_id}', [AvailabilityController::class, 'availabilityparking'])->name('availability.availabilityparking');
        Route::get('/availability.summary/{building_id}', [AvailabilityController::class, 'summary'])->name('availability.summary');
        // Sales Report Excel
        Route::get('/sales-report/all/{building_id}', [SalesReportController::class, 'allsales'])->name('sales.all');
        Route::get('/sales.commercial/{building_id}', [SalesReportController::class, 'commercial'])->name('sales.commercial');
        Route::get('/sales.apartment/{building_id}', [SalesReportController::class, 'apartment'])->name('sales.apartment');
        Route::get('/sales.parking/{building_id}', [SalesReportController::class, 'parking'])->name('sales.parking');
        Route::get('/sales.summary/{building_id}', [SalesReportController::class, 'summary'])->name('sales.summary');
        // Sales Return Report Excel
        Route::get('/sales-return-report/returnreportall/{building_id}', [SalesReturnReportController::class, 'returnreportall'])->name('salesreturn.returnreportall');
        Route::get('/salesreturn.commercial/{building_id}', [SalesReturnReportController::class, 'commercialreturn'])->name('salesreturn.commercial');
        Route::get('/salesreturn.apartment/{building_id}', [SalesReturnReportController::class, 'apartmentreturn'])->name('salesreturn.apartment');
        Route::get('/salesreturn.parking/{building_id}', [SalesReturnReportController::class, 'parkingreturn'])->name('salesreturn.parking');
        // Exchange Report Excel
        Route::get('/exchange-report/exchange_report/{building_id}', [ExchangeReportController::class, 'exchangereturnreport'])->name('exchangereport.exchange_report');
        Route::get('/exchangereturn.exchangeandreturnsummary/{building_id}', [ExchangeReportController::class, 'exchangeandreturnsummary'])->name('exchangereport.exchangeandreturnsummary');


        // Cashbook Excel
        Route::get('/cash-book/cash_book/{building_id}', [CashBookController::class, 'cashbook'])->name('cashbook.cash_book');
        Route::get('/cashbook.BasheerCurrentAccount/{building_id}', [CashBookController::class, 'basheercurrentaccount'])->name('cashbook.BasheerCurrentAccount');
        Route::get('/cashbook.PavoorCurrentAccount/{building_id}', [CashBookController::class, 'pavoorcurrentaccount'])->name('cashbook.PavoorCurrentAccount');

        // Accounts payable excel

        Route::get('/accounts-payable/statementall/{building_id}', [AccountsPayableController::class, 'statementall'])->name('Accountspayable.statementall');
        Route::get('/Accountspayable/statementcheque/{building_id}', [AccountsPayableController::class, 'statementcheque'])->name('Accountspayable.statementcheque');
        Route::get('/Accountspayable/statementcash/{building_id}', [AccountsPayableController::class, 'statementcash'])->name('Accountspayable.statementcash');

        Route::get('/Accountspayable/download/{buildingId}', [AccountsPayableController::class, 'statementall'])->name('Accountspayable.downloadStatement');

        Route::get('/pdf/exchange_report_pdf/{buildingId}', [ExchangeReportController::class, 'generatePDF'])->name('exchange_report.pdf');

        Route::get('/pdf/exchange_return_summary_pdf/{buildingId}', [ExchangeReportController::class, 'exchangeandreturnsummaryPdf'])->name('exchange_return_summary.pdf');

        Route::get('/pdf/total_breakup_pdf/{buildingId}',[TotalBuildUpAreaController::class,'totalbuildupPDF'])->name('total_breakup.pdf');

        Route::get('/pdf/apartment_break_up_pdf/{buildingId}',[TotalBuildUpAreaController::class,'apartmentbreakupPDF'])->name('apartment_break_up.pdf');

        Route::get('/pdf/commercial_break_up_pdf/{buildingId}',[TotalBuildUpAreaController::class,'commercialbreakupPDF'])->name('commercial_break_up.pdf');

        Route::get('/pdf/parking_break_up_pdf/{buildingId}',[TotalBuildUpAreaController::class,'parkingbreakupPDF'])->name('parking_break_up.pdf');

        Route::get('/pdf/summary_break_up_pdf/{buildingId}',[TotalBuildUpAreaController::class,'summarybreakupPDF'])->name('summary_break_up.pdf');

        Route::get('/pdf/changes_in_expected_amount_pdf/{buildingId}',[TotalBuildUpAreaController::class,'changesinexpectedamountPDF'])->name('changes_in_expected_amount.pdf');

        Route::get('/pdf/balance_summary_break_up_pdf/{buildingId}',[TotalBuildUpAreaController::class,'balancesummaryPDF'])->name('balance_summary_break_up.pdf');

        Route::get('/pdf/total_availability_pdf/{buildingId}',[AvailabilityController::class,'totalavailabilityPDF'])->name('total_availability.pdf');

        Route::get('/pdf/availability_flat_pdf/{buildingId}',[AvailabilityController::class,'availabilityflatPDF'])->name('availability_flat.pdf');

        Route::get('/pdf/availability_shop_pdf/{buildingId}',[AvailabilityController::class,'availabilityshopPDF'])->name('availability_shop.pdf');

        Route::get('/pdf/availability_parking/{buildingId}',[AvailabilityController::class,'availabilityparkingPDF'])->name('availability_parking.pdf');

        Route::get('/pdf/availability_summary/{buildingId}',[AvailabilityController::class,'availabilitysummaryPDF'])->name('availability_summary.pdf');

        Route::get('/pdf/sales_all_report_pdf/{buildingId}',[SalesReportController::class,'salesallPDF'])->name('sales_all_report.pdf');

        Route::get('/pdf/sales_apartment_report_pdf/{buildingId}',[SalesReportController::class,'salesapartmentPDF'])->name('sales_apartment_report.pdf');

        Route::get('/pdf/sales_commercial_report_pdf/{buildingId}',[SalesReportController::class,'salescommercialPDF'])->name('sales_commercial_report.pdf');

        Route::get('/pdf/sales_parking_report_pdf/{buildingId}',[SalesReportController::class,'salesparkingPDF'])->name('sales_parking_report.pdf');

        Route::get('/pdf/sales_summary_report_pdf/{buildingId}',[SalesReportController::class,'salessummaryPDF'])->name('sales_summary_report.pdf');

        Route::get('/pdf/sales_return_report_all_pdf/{buildingId}',[SalesReturnReportController::class,'salesreturnallPDF'])->name('sales_return_report_all.pdf');

        Route::get('/pdf/sales_return_parking_report_pdf/{buildingId}',[SalesReturnReportController::class,'salesreturnparkingPDF'])->name('sales_return_parking_report.pdf');

        Route::get('/pdf/sale_return_commercial_report_pdf/{buildingId}',[SalesReturnReportController::class,'salesreturncommercialPDF'])->name('sales_return_commercial_report.pdf');

        Route::get('/pdf/sales_return_apartment_report_pdf/{buildingId}',[SalesReturnReportController::class,'salesreturnapartmentPDF'])->name('sales_return_apartment_report.pdf');

        Route::get('/pdf/bank_account_report_pdf/{buildingId}',[BankAccountController::class,'bankaccountPDF'])->name('bank_account_report.pdf');

        Route::get('/pdf/banknames_report_pdf/{buildingId}',[BankAccountController::class,'banknamesreportPDF'])->name('banknames_report.pdf');

        


      









    });
});