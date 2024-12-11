<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Room;
use App\Models\Building;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB; 


class StatementController extends Controller
{
        
    public function cash(Sale $sale)
    {

        $cashInstallments = $sale->cash_installments;
    
        // Get the first and last installment dates
        $firstInstallmentDate = $cashInstallments->first()->installment_date ?? now();
        $lastInstallmentDate = $cashInstallments->last()->installment_date ?? now();
    
        // Fetch the first installment amount where installment_number = 1 and status = 'paid'
        $firstCreditAmount = $cashInstallments
            ->where('installment_number', 1)
            ->where('status', 'paid')
            ->first()
            ->installment_amount ?? 0;
    
        // Calculate the total installment amount for the sale
        $totalInstallmentAmount = $cashInstallments->sum('installment_amount');
    
        // Adjust the initial balance using the first credit amount
        $initialBalance = $totalInstallmentAmount - $firstCreditAmount;
    
        // Calculate total debit and credit for display
        $totalDebit = $cashInstallments->sum('debit');
        $totalCredit = $cashInstallments->where('status', 'paid')->sum('installment_amount');
    
        return view('statements.cash', [
            'sale' => $sale,
            'cashInstallments' => $cashInstallments,
            'firstInstallmentDate' => $firstInstallmentDate,
            'lastInstallmentDate' => $lastInstallmentDate,
            'totalCredit' => $totalCredit,
            'initialBalance' => $initialBalance,
            'firstCreditAmount' => $firstCreditAmount, // Pass the first credit amount to the view
            'page' => 'cash',
            'title' => 'Cash Statement',
            'balance' => $totalDebit - $totalCredit,
        ]);
    }
    
        
    public function downloadCashStatement(Sale $sale)
    {
        $cashInstallments = $sale->cash_installments;
        
        $totalCredit = $cashInstallments->where('status', 'paid')->sum('installment_amount');
        $totalDebit = $cashInstallments->sum('debit');
        $balance = $totalDebit - $totalCredit;

        $firstInstallmentDate = $cashInstallments->first()->installment_date;
        $lastInstallmentDate = $cashInstallments->last()->installment_date;

        $pdf = Pdf::loadView('pdf.cash_pdf', compact(
            'sale', 'cashInstallments', 'totalDebit', 'totalCredit', 'balance', 'firstInstallmentDate', 'lastInstallmentDate'
        ));
        

        return $pdf->download('cash_statement.pdf');
    }

    

    public function cheque(Sale $sale)
    {
        $chequeInstallments = $sale->installments;
        $firstInstallmentDate = $chequeInstallments->first()->installment_date ?? now();
        $lastInstallmentDate = $chequeInstallments->last()->installment_date ?? now();
        
        // Calculate the total debit and credit
        $totalDebit = $chequeInstallments->sum('debit');
        $totalCredit = $chequeInstallments->where('status', 'paid')->sum('installment_amount');
    
        return view('statements.cheque', [
            'sale' => $sale,
            'chequeInstallments' => $chequeInstallments,
            'firstInstallmentDate' => $firstInstallmentDate,
            'lastInstallmentDate' => $lastInstallmentDate,
            'totalDebit' => $totalDebit,
            'totalCredit' => $totalCredit,
            'page' => 'cheque',
            'title' => 'Cheque Statement',
        ]);
    }
    public function downloadChequeStatement($id)
    {
        // Retrieve the sale and related data
        $sale = Sale::findOrFail($id);
        $installments = $sale->installments; // Assuming a relationship
    
        // Other variables
        $firstInstallmentDate = $installments->first()->installment_date ?? now();
        $lastInstallmentDate = $installments->last()->installment_date ?? now();
        $totalDebit = $installments->sum('debit');
        $totalCredit = $installments->sum('credit');
        $title = "Cheque Statement for Sale ID: $id"; // Set the title as desired
        $page = 'cheque-statement'; 
        // Generate the PDF
        $pdf = PDF::loadView('pdf.cheque_pdf', [
            'sale' => $sale,
            'installments' => $installments,
            'firstInstallmentDate' => $firstInstallmentDate,
            'lastInstallmentDate' => $lastInstallmentDate,
            'totalDebit' => $totalDebit,
            'totalCredit' => $totalCredit,
            'title' => $title,
            'page' => $page,
        ]);
    
        // Return the PDF as a download
        return $pdf->download('cheque_statement.pdf');
    }
    
    

    public function all(Sale $sale)
    {
        // Retrieve all cash and cheque installments related to the sale
        $cashInstallments = $sale->cash_installments;
        $chequeInstallments = $sale->installments;
    
        // Merge all transactions (cash and cheque installments)
        $transactions = collect();
    
        // Add cash installments to transactions
        foreach ($cashInstallments as $installment) {
            $transactions->push([
                'date' => $installment->installment_date,
                'description' => "{$installment->installment_number} Installement (Cash)",
                'payment_type' => 'Cash',
                'debit' => 0,
                'credit' => $installment->installment_amount,
            ]);
        }
    
        // Add cheque installments to transactions
        foreach ($chequeInstallments as $installment) {
            $transactions->push([
                'date' => $installment->installment_date,
                'description' => "{$installment->installment_number} Installement (Cheque)",
                'payment_type' => 'Cheque',
                'debit' => 0,
                'credit' => $installment->installment_amount,
            ]);
        }
    
        // Add initial sales amounts
        $transactions->push([
            'date' => $sale->sale_date,
            'description' => 'Sales By Cash',
            'payment_type' => 'Cash',
            'debit' => $sale->cash_amount,
            'credit' => 0,
        ]);
    
        $transactions->push([
            'date' => $sale->sale_date,
            'description' => 'Sales',
            'payment_type' => 'Cheque',
            'debit' => $sale->cheque_amount,
            'credit' => 0,
        ]);
    
        // Sort transactions by date
        $transactions = $transactions->sortBy('date');
    
        // Calculate running balance
        $balance = 0;
        foreach ($transactions as &$transaction) {
            $balance += $transaction['debit'] - $transaction['credit'];
            $transaction['balance'] = $balance;
        }
        $title= "all statement";
    
        return view('statements.all', [
            'sale' => $sale,
            'transactions' => $transactions,
            'page' => 'all',
            'title' => $title,

        ]);
    }
    

    public function summary(Sale $sale)
    {
        // Calculate values based on the Sale model and related data
        $saleAmount = $sale->amount; // Assuming this is the total sale amount
        $cashReceived = $sale->cash_received; // Total cash received
        $chequeReceived = $sale->cheque_received; // Total cheque received
        $gst = $sale->gst; // Assuming this is the GST amount
        $totalReceivable = $saleAmount + $gst; // Total amount including GST
        $totalReceived = $cashReceived + $chequeReceived; // Total received amount
        $balanceReceivable = $totalReceivable - $totalReceived; // Remaining balance
        $title= "summary";

        return view('statements.summary', [
            'sale' => $sale,
            'saleAmount' => $saleAmount,
            'cashReceived' => $cashReceived,
            'chequeReceived' => $chequeReceived,
            'gst' => $gst,
            'totalReceivable' => $totalReceivable,
            'totalReceived' => $totalReceived,
            'balanceReceivable' => $balanceReceivable,
            'page' => 'summary',
            'title' => $title,

        ]);
    }
        
    public function commercialSalesReport()
    {
        $commercialSales = Sale::with('room')
            ->whereHas('room', function ($query) {
                // Additional filters on room, if needed
            })
            ->whereNull('deleted_at') 
            ->get();
    
        // Group sales by floor and room type
        $groupedSalesByFloor = $commercialSales->groupBy(function ($sale) {
            return $sale->room->room_floor ?? 'N/A'; // Group by floor, or 'N/A' if floor is missing
        })->map(function ($sales) {
            return $sales->groupBy(function ($sale) {
                return $sale->room->room_type ?? 'N/A'; // Group each floor's sales by room type
            });
        });
    
        // Calculate totals
        $totalSalesAmount = $commercialSales->sum('final_amount'); 
        $totalCashReceived = $commercialSales->sum('cash_received'); 
        $totalChequeReceived = $commercialSales->sum('cheque_received');
        $totalGST = $commercialSales->sum('gst');
        $totalSqft = $commercialSales->sum(function ($sale) {
            if (!$sale->room) {
                return 0;
            }
            
            // Choose the area based on the area calculation type
            return $sale->area_calculation_type === 'super_build_up_area'
                ? $sale->build_up_area
                : ($sale->area_calculation_type === 'carpet_area' ? $sale->carpet_area : 0);
        });
    
        // Calculate receivables
        $totalReceivable = $totalSalesAmount + $totalGST; 
        $totalReceived = $totalCashReceived + $totalChequeReceived; 
        $balanceReceivable = $totalReceivable - $totalReceived;
        
    
        // Return the view with the calculated data
        return view('statements.commercial-sales-report', [
            'sales' => $commercialSales,
            'groupedSalesByFloor' => $groupedSalesByFloor, // Pass the new variable to the view
            'totalSalesAmount' => $totalSalesAmount,
            'totalCashReceived' => $totalCashReceived,
            'totalChequeReceived' => $totalChequeReceived,
            'totalGST' => $totalGST,
            'totalReceivable' => $totalReceivable,
            'totalReceived' => $totalReceived,
            'balanceReceivable' => $balanceReceivable,
            'totalSqft' => $totalSqft,
            'page' => 'summary',
            'title' => 'All Commercial Sales Report',
        ]);
    }

        
    public function shopSalesReport()
    {
        // Fetch sales data for room_type "Shops" and group by room_floor
        $shopSales = Sale::with('room')
            ->whereHas('room', function($query) {
                $query->where('room_type', 'Shops');
            })->get();
    
        // Calculate the totals
        $totalSalesAmount = $shopSales->sum('sale_amount');
        $totalSqft = $shopSales->sum(function ($sale) {
            // Determine sqft based on area_calculation_type
            return $sale->area_calculation_type === 'super_build_up_area'
                ? $sale->build_up_area
                : ($sale->area_calculation_type === 'carpet_area' ? $sale->carpet_area : 0);
        });
        $totalGST = $shopSales->sum('gst_amount');
        $totalCashReceived = $shopSales->sum('cash_received');
        $totalChequeReceived = $shopSales->sum('cheque_received');
        $totalReceivable = $totalSalesAmount;
        $balanceReceivable = $totalReceivable - ($totalCashReceived + $totalChequeReceived);
    
        // Group the sales by floor
        $groupedSalesByFloor = $shopSales->groupBy(function ($sale) {
            return $sale->room->room_floor ?? 'Unknown'; // Group by room_floor, defaulting to 'Unknown' if null
        });
    
        // Define title and page variables
        $title = 'Shop Sales Report';
        $page = 'shop-sales-report';
    
        // Return the view with all calculated data
        return view('statements.shop-sales-report', compact(
            'groupedSalesByFloor',
            'totalSalesAmount',
            'totalSqft',
            'totalGST',
            'totalCashReceived',
            'totalChequeReceived',
            'totalReceivable',
            'balanceReceivable',
            'title',
            'page'
        ));
    }
    
        
    public function apartmentSalesReport()
    {
        // Fetch sales for rooms where room_type is "Flat"
        $sales = Sale::whereHas('room', function ($query) {
            $query->where('room_type', 'Flat');
        })->with('room')->get();

        // Group the sales by floor number
        $groupedSalesByFloor = $sales->groupBy(fn($sale) => $sale->room->room_floor);

        // Calculate total amounts
        $totalSalesAmount = $sales->sum('final_amount');
        $totalSqft = $sales->sum(fn($sale) => $sale->area_calculation_type === 'super_build_up_area'
            ? $sale->room->build_up_area
            : ($sale->area_calculation_type === 'carpet_area'
                ? $sale->room->carpet_area
                : 0));

        $title = 'Flat Sales Report';
        $page = 'Flat-sales-report';

        return view('statements.apartments-sales-report', compact('groupedSalesByFloor', 'totalSalesAmount', 'totalSqft','title','page',));
    }
        
    public function commercialSalesSummary()
    {
        // Fetch all commercial sales with room information, excluding deleted records
        $commercialSales = Sale::with('room')
            ->whereNull('deleted_at')
            ->get();
    
        // Group sales by floor and then by room type (if applicable)
        $groupedSalesByFloor = $commercialSales->groupBy(function ($sale) {
            return $sale->room->room_floor ?? 'N/A';
        })->map(function ($sales) {
            return $sales->groupBy(function ($sale) {
                return $sale->room->room_type ?? 'N/A';
            });
        });
    
        // Calculate totals for sales amounts, received amounts, and GST
        $totalSalesAmount = $commercialSales->sum('final_amount');
        $totalCashReceived = $commercialSales->sum('cash_received');
        $totalChequeReceived = $commercialSales->sum('cheque_received');
        $totalGST = $commercialSales->sum('gst');
    
        // Calculate total area in square feet based on the area calculation type
        $totalSqft = $commercialSales->sum(function ($sale) {
            // Ensure that the sale has an associated room
            if (!$sale->room) {
                return 0;
            }
            // Use the appropriate area calculation based on the type
            return $sale->area_calculation_type === 'super_build_up_area'
                ? $sale->room->build_up_area // Assuming `build_up_area` is a property of the room
                : ($sale->area_calculation_type === 'carpet_area' ? $sale->room->carpet_area : 0);
        });
    
        // Calculate total receivable amount and balance receivable
        $totalReceivable = $totalSalesAmount + $totalGST;
        $totalReceived = $totalCashReceived + $totalChequeReceived;
        $balanceReceivable = $totalReceivable - $totalReceived;
    
        // Return the summary view with calculated totals and grouped sales data
        return view('statements.commercialsummary', [
            'sales' => $commercialSales,
            'groupedSalesByFloor' => $groupedSalesByFloor,
            'totalSalesAmount' => $totalSalesAmount,
            'totalCashReceived' => $totalCashReceived,
            'totalChequeReceived' => $totalChequeReceived,
            'totalGST' => $totalGST,
            'totalReceivable' => $totalReceivable,
            'totalReceived' => $totalReceived,
            'balanceReceivable' => $balanceReceivable,
            'totalSqft' => $totalSqft,
            'page' => 'commercial-sales-summary',
            'title' => 'Commercial Sales Summary',
        ]);
    }
    public function salesSummary()
    {
        // Initialize the title and page variables
        $title = 'Commercial Sales Summary';  // Set the title for the page
        $page = 'sales_summary';  // Optional identifier for the page
    
        // Initialize the total shop area and other required variables
        $totalShopSqft = 0;
        $totalSalesAmount = Sale::sum('total_amount');
    
        // Fetch all sales data with related room data
        $sales = Sale::with('room')->get();
    
        // Group sales by floor
        $groupedSalesByFloor = $sales->groupBy(function ($sale) {
            return $sale->room->floor ?? 'Unknown'; // Group by floor, default to 'Unknown' if not set
        });
    
        // Calculate the total shop area for shops
        foreach ($sales as $sale) {
            $room = $sale->room;
    
            if ($room && $room->room_type === 'Shop') {
                if ($room->area_calculation_type === 'super_build_up_area') {
                    $totalShopSqft += $room->build_up_area;
                } elseif ($room->area_calculation_type === 'carpet_area') {
                    $totalShopSqft += $room->carpet_area;
                }
            }
        }
    
        // Pass the title, page, and other data to the view
        return view('statements.commercialsummary', [
            'title' => $title,
            'page' => $page,
            'totalShopSqft' => $totalShopSqft,
            'totalSalesAmount' => $totalSalesAmount,
            'groupedSalesByFloor' => $groupedSalesByFloor,
        ]);
    }

    public function displayCustomerInfo($saleId)
    {
        // Fetch the sale details, including room and installments
        $sale = Sale::with('installments')->findOrFail($saleId);

        // Retrieve rooms associated with the same customer based on name and contact
        $matchingRooms = Room::whereHas('sale', function($query) use ($sale) {
            $query->where('customer_name', $sale->customer_name)
                ->where('customer_contact', $sale->customer_contact);
        })->get();

        // Format room numbers for display as "Shop No: 21,22,23,24 & 25"
        $roomNumbers = $matchingRooms->pluck('room_number')->join(', ');

        // Calculate totals and additional information as needed
        $totalCashExpenses = DB::table('cash_expenses')
            ->where('sale_id', $saleId)
            ->sum('cash_expense_amount');

        $totalChequeAmount = DB::table('installments')
            ->where('sale_id', $saleId)
            ->sum('installment_amount');

        $totalCashAmount = DB::table('cash_installments')
            ->where('sale_id', $saleId)
            ->sum('installment_amount');

        // Additional calculations
        $totalWithAdditional = $totalCashExpenses + $totalChequeAmount;
        
        $additionalWork = $totalCashExpenses + $totalChequeAmount;

        $totalCashValue = $sale->total_cash_value ?? 0;
        $cashValueAmount = $sale->cash_value_amount ?? 0;
        $additionalWorkCash = $totalCashValue - $cashValueAmount;

        $page = 'customer-info';
        $room = $matchingRooms->first();
        

        return view('customers.info', compact(
            'sale', 
            'page', 
            'roomNumbers', 
            'totalCashExpenses', 
            'totalChequeAmount', 
            'totalCashAmount',
            'totalWithAdditional',
            'room' ,
            'additionalWorkCash',
            'additionalWork', 


        ));
    }

// availability section 

// app/Http/Controllers/RoomController.php
    // app/Http/Controllers/RoomController.php
    public function showAvailableRooms($building_id)
    {
        $building = Building::findOrFail($building_id);
    
        // Fetch available rooms grouped by room floor first, then by room type in the specified order
        $availableRooms = Room::where('building_id', $building_id)
                            ->where('status', 'available')
                            ->orderBy('room_floor')  // First sort by floor
                            ->orderByRaw("FIELD(room_type, 'Flat', 'Shops', 'Kiosk', 'Chair space', 'Table space')")  // Then by custom room type order
                            ->get();
                        
        $page = 'availability';
        $title = 'Availability';
    
        return view('statements.available', [
            'building' => $building,
            'availableRooms' => $availableRooms,
            'title' => $title,
            'page' => $page,
        ]);
    }
        
    public function availableShops($buildingId)
    {
        $building = Building::findOrFail($buildingId);
        $availableShops = $building->rooms()->where('room_type', 'Shop')->where('status', 'available')->get();
        $page = 'availability-Shops';
        $title = 'availability-Shops';
        return view('statements.available_shops', compact('building', 'availableShops','page','title'));
    }
    
        
    public function showAvailableFlats(Building $building)
    {
        $availableRooms = $building->rooms()
            ->where('status', 'available')
            ->where('room_type', 'Flat')
            ->orderBy('room_floor', 'asc')
            ->get();

        return view('statements.available_flats', [
            'building' => $building,
            'availableRooms' => $availableRooms,
            'title' => 'Available Flats',
            'page' => 'flats'
        ]);
    }
    public function confirmExchange($saleId)
    {
        // Fetch the sale details, including room and installments
        $sale = Sale::with('installments', 'cash_installments')->findOrFail($saleId);
    
        // Retrieve rooms associated with the same customer based on name and contact
        $matchingRooms = Room::whereHas('sale', function($query) use ($sale) {
            $query->where('customer_name', $sale->customer_name)
                ->where('customer_contact', $sale->customer_contact);
        })->get();
    
        // Format room numbers for display as "Shop No: 21,22,23,24 & 25"
        $roomNumbers = $matchingRooms->pluck('room_number')->join(', ');
    
        // Calculate totals and additional information as needed
        $totalCashExpenses = DB::table('cash_expenses')
            ->where('sale_id', $saleId)
            ->sum('cash_expense_amount');
    
        $totalChequeAmount = DB::table('installments')
            ->where('sale_id', $saleId)
            ->sum('installment_amount');
    
        $totalCashAmount = DB::table('cash_installments')
            ->where('sale_id', $saleId)
            ->sum('installment_amount');
    
        // Additional calculations
        $totalWithAdditional = $totalCashExpenses + $totalChequeAmount;
        $additionalWork = $totalCashExpenses + $totalChequeAmount;
    
        $totalCashValue = $sale->total_cash_value ?? 0;
        $cashValueAmount = $sale->cash_value_amount ?? 0;
        $additionalWorkCash = $totalCashValue - $cashValueAmount;
    
        $page = 'customer-exchange';
        $title = 'customer-exchange';
        $room = $matchingRooms->first();
        $building = $room->building;

        return view('customers.confirmexchange', compact(
            'sale', 
            'page', 
            'roomNumbers', 
            'totalCashExpenses', 
            'totalChequeAmount', 
            'totalCashAmount',
            'totalWithAdditional',
            'room', 
            'additionalWorkCash',
            'additionalWork',
            'building',
            'title',
        ));
    }
    
    public function updateExchange($saleId)
{
    $sale = Sale::findOrFail($saleId);

        // Update the exchange status (e.g., mark it as confirmed)
        $sale->exchange_confirmed = true;
        $sale->save();

        // Define page and title
        $page = "updateExchange";
        $title = "updateExchange";

        // Redirect back or to another page with additional data
        return redirect()->route('admin.customer.info', ['saleId' => $saleId])
                         ->with('status', 'Exchange confirmed successfully')
                         ->with('page', $page)
                         ->with('title', $title);
}
public function showAvailability($building_id , $sale_id)
{
    $building = Building::findOrFail($building_id);
    $sale = Sale::findOrFail($sale_id);

    // Additional logic to determine available rooms for exchange
    $availableRooms = Room::where('building_id', $building_id)
        ->where('status', 'available') // Example condition
        ->get();

    $title = 'showAvailableRooms'; 
    $page = 'showAvailableRooms'; 

    return view('customers.exchangeavailability', compact('building', 'sale', 'availableRooms','title','page'));
}
public function showExchangeSellPage($id, $buildingId, $roomId)
{
    // Retrieve the sale, building, and room using the provided IDs
    $sale = Sale::findOrFail($id);
    $building = Building::findOrFail($buildingId);
    $room = Room::findOrFail($roomId);

    // Pass the retrieved models to the view
    
    return view('customers.exchangeavailability', [
        'sale' => $sale,
        'building' => $building,
        'room' => $room,
    ]);
}
}