<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Parking;
use App\Models\Partner;
use App\Models\Sale;
use App\Models\PartnerDistribution;
use Carbon\Carbon;
use App\Models\CashInstallment;
use App\Models\Installment;
use App\Models\CashExpense;
use App\Models\ChequeExpense;
Use Illuminate\Support\Facades\Log;





use Illuminate\Support\Facades\DB;



class ExchangeController extends Controller
{
  public function showExchangeSellPage(Request $request)
{
    $saleId = $request->input('sale_id'); // Retrieve sale_id from the URL
    $sale = Sale::find($saleId); // Retrieve the sale using the sale_id
    $room = Room::first(); // Fetch a specific room based on your logic
    $availableFloors = Parking::distinct()->pluck('floor_number')->toArray();
    $partners = Partner::all();
    $availableParkings = Parking::where('status', 'available')->get();
    $exchangedToSaleId = $saleId; // Dynamically set exchangedToSaleId from sale_id
    $title = "showExchangeSellPage";
    $page = "showExchangeSellPage";

    // Pass the variables to the view
    return view('customers.exchangesell', compact(
        'sale',
        'room',
        'availableFloors',
        'availableParkings',
        'partners',
        'exchangedToSaleId', // Pass the dynamically assigned exchangedToSaleId
        'page',
        'title',
        'saleId'
    ));
}

    
    public function store(Request $request)
    {
        DB::beginTransaction(); // Start the database transaction

        // dd($request->all());
        // Decode JSON strings into arrays
    try {
        $request->merge([
            'partner_distribution' => json_decode($request->partner_distribution, true),
            'partner_percentages' => json_decode($request->partner_percentages, true),
            'partner_amounts' => json_decode($request->partner_amounts, true),
        ]);
    
        // Validate the incoming request data
        $validatedData = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_contact' => 'required|string|max:255',
            'sale_amount' => 'required|numeric',
            'area_calculation_type' => 'required|string',
            'flat_build_up_area' => 'nullable|numeric',
            'flat_carpet_area' => 'nullable|numeric',
            'total_amount' => 'nullable|numeric',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric',
            'final_amount' => 'nullable|numeric',
            'cash_value_percentage' => 'nullable|numeric|min:0|max:100',
            'cash_value_amount' => 'nullable|numeric',
            'total_cash_value' => 'nullable|numeric',
            'total_received_amount' => 'nullable|numeric',
            'remaining_cash_value' => 'nullable|numeric',
            'gst_percentage' => 'nullable|numeric|min:0|max:100', 
            'gst_amount' => 'nullable|numeric', 
            'total_cheque_value_with_gst' => 'nullable|numeric',
    
    
            'partner_distribution' => 'required|array',
            'partner_percentages' => 'required|array',
            'partner_amounts' => 'required|array',
            'partner_distribution.*' => 'exists:partners,id',
            'partner_percentages.*' => 'numeric|min:0|max:100',
            'partner_amounts.*' => 'numeric|min:0',
    
            'cash_expense_descriptions' => 'nullable|array',
            'cash_expense_percentages' => 'nullable|array',
            'cash_expense_amounts' => 'nullable|array',
            'cash_expense_descriptions.*' => 'string|max:255',
            'cash_expense_percentages.*' => 'numeric|min:0|max:100',
            'cash_expense_amounts.*' => 'numeric|min:0',
    
            'cheque_expense_descriptions' => 'nullable|array',
            'cheque_expense_amounts' => 'nullable|array',
            'cheque_expense_descriptions.*' => 'string|max:255',
            'cheque_expense_amounts.*' => 'numeric|min:0',
            'total_cheque_value_with_additional' => 'nullable|numeric',
            'total_cheque_value' => 'nullable|numeric',

            'received_cheque_value' => 'nullable|numeric',
            'balance_amount' => 'nullable|numeric',

            'loan_type' => 'nullable|string',
            'other_loan_description' => 'nullable|string',
            'installment_frequency' => 'nullable|string',
            'installment_date' => 'nullable|date',
            'no_of_installments' => 'nullable|integer',
            'installment_amount' => 'nullable|numeric',
            'grand_total_amount' => 'nullable|numeric',

            'cash_installment_value' => 'nullable|numeric|min:0',
            'cash_loan_type' => 'nullable|string',
            'other_loan_description_cash' => 'nullable|string',
            'cash_installment_frequency' => 'nullable|string',
            'cash_installment_start_date' => 'nullable|date',
            'cash_no_of_installments' => 'nullable|integer|min:1',
            'cash_installment_amount' => 'nullable|numeric|min:0',

            'parking_floor' => 'required|integer|exists:parkings,floor_number', // Validate floor number
            'parking_id' => 'required|integer|exists:parkings,id',             // Validate parking slot ID
            'parking_amount_cheque' => 'nullable|numeric|min:0',
            'parking_amount_cash' => 'nullable|numeric|min:0',  // Add this rule
            'cheque_description' => 'nullable|string|max:1000',  // Adjust max length if needed


            'land_descriptions' => 'nullable|array',
            'land_amounts' => 'nullable|array',
            'land_descriptions.*' => 'string|max:255',
            'land_amounts.*' => 'numeric|min:0',

            'land_description' => 'nullable|string|max:1000',
            'land_amount' => 'nullable|numeric|min:0', // Validation for land amount
            'exchangestatus' => 'nullable|string|in:NX,EX', // Validation for exchangestatus
            'exchange_sale_id' => 'required|exists:sales,id',




        ]);
    
        // Update the room status
        $room = Room::find($request->room_id);
        $room->status = 'sold';
        $room->save();
    
        $building_id = $room->building_id;
        $chequeExpenseAmounts = $request->cheque_expense_amounts; // This is an array
        $chequeExpenseAmount = !empty($chequeExpenseAmounts) ? (float)$chequeExpenseAmounts[0] : 0;

        // Store the sale
        $sale = Sale::create(array_merge($validatedData, [
            'cheque_expense_amounts' => $chequeExpenseAmount,
            'cash_installment_value' => $request->cash_installment_value,
            'cash_loan_type' => $request->cash_loan_type,
            'other_loan_description_cash' => $request->other_loan_description_cash,
            'cash_installment_frequency' => $request->cash_installment_frequency,
            'cash_installment_start_date' => $request->cash_installment_start_date,
            'cash_no_of_installments' => $request->cash_no_of_installments,
            'cash_installment_amount' => $request->cash_installment_amount,
            'parking_floor' => $request->parking_floor,   // Add parking floor
            'parking_id' => $request->parking_id,         // Add parking ID
            'parking_amount_cheque' => $request->parking_amount_cheque,
            'parking_amount_cash' => $request->input('parking_amount_cash'),
            'cheque_description' => $request->input('cheque_description'),
            'land_description' => $request->input('land_description'),
            'land_amount' => $request->input('land_amount'),
            'exchangestatus' => 'NX', // Default to 'NX'
            $exchangeSaleId = $request->input('exchange_sale_id'),





        ]));
        
        if ($request->exchange_sale_id) {
            $exchangeSale = Sale::find($request->exchange_sale_id);
            $exchangeRoom = Room::find($exchangeSale->room_id);

            // Change the status of the exchanged room to 'available'
            if ($exchangeRoom) {
                $exchangeRoom->status = 'available';
                $exchangeRoom->save();
            }

            // Update the exchange status of the new sale
            $sale->exchangestatus = 'EX';
            $sale->save();
        }
        // Store partner distributions
        foreach ($request->partner_distribution as $index => $partnerId) {
            PartnerDistribution::create([
                'sale_id' => $sale->id,
                'partner_id' => $partnerId,
                'percentage' => $request->partner_percentages[$index] ?? 0,
                'amount' => $request->partner_amounts[$index] ?? 0,
            ]);
        }
    
        // Store cash expenses if they exist
        if ($request->has('expense_descriptions')) {
            foreach ($request->expense_descriptions as $index => $description) {
                if ($description) {
                    try {
                        CashExpense::create([
                            'sale_id' => $sale->id, // Link the expense to the sale
                            'cash_expense_description' => $description,
                            'cash_expense_percentage' => $request->expense_percentages[$index] ?? null,
                            'cash_expense_amount' => $request->expense_amounts[$index] ?? null, // Save the expense amount
                        ]);
                    } catch (\Exception $e) {
                        dd('Error inserting cash expense:', $e->getMessage());
                    }
                }
            }
        }

    
        // Store cheque expenses if they exist
        if ($request->has('cheque_expense_descriptions')) {
            foreach ($request->cheque_expense_descriptions as $index => $description) {
                ChequeExpense::create([
                    'sale_id' => $sale->id,
                    'cheque_expense_descriptions' => $description,
                    'cheque_expense_amounts' => $request->cheque_expense_amounts[$index] ?? 0,
                ]);
            }
        }
        if ($request->filled(['installment_frequency', 'installment_amount', 'installment_date', 'no_of_installments'])) {
            $frequencyInput = $request->input('installment_frequency'); // e.g., '3 months'
            $installmentAmount = $request->input('installment_amount');
            $startDate = Carbon::parse($request->input('installment_date'));
            $noOfInstallments = $request->input('no_of_installments');

            // Parse frequency, assuming it's in 'X months' format
            if (preg_match('/(\d+)\s*month/i', $frequencyInput, $matches)) {
                $interval = intval($matches[1]);
            } else {
                // Handle other frequency formats or set default
                $interval = 1; // default to 1 month
            }

            for ($i = 0; $i < $noOfInstallments; $i++) {
                Installment::create([
                    'sale_id' => $sale->id,
                    'installment_frequency' => $frequencyInput,
                    'installment_date' => $startDate->copy()->addMonths($interval * $i),
                    'installment_number' => $i + 1,
                    'installment_amount' => $installmentAmount,
                    'status' => 'unpaid',
                ]);
            }
        }
        
        if ($request->filled(['cash_installment_frequency', 'cash_installment_amount', 'cash_installment_start_date', 'cash_no_of_installments'])) {
            $cashFrequencyInput = $request->input('cash_installment_frequency');
            $cashInstallmentAmount = $request->input('cash_installment_amount');
            $cashStartDate = Carbon::parse($request->input('cash_installment_start_date'));
            $cashNoOfInstallments = $request->input('cash_no_of_installments');

            // Assume frequency is in 'X months' format
            if (preg_match('/(\d+)\s*month/i', $cashFrequencyInput, $matches)) {
                $cashInterval = intval($matches[1]);
            } else {
                $cashInterval = 1; // Default to 1 month
            }

            for ($i = 0; $i < $cashNoOfInstallments; $i++) {
                CashInstallment::create([
                    'sale_id' => $sale->id,
                    'installment_frequency' => $cashFrequencyInput,
                    'installment_date' => $cashStartDate->copy()->addMonths($cashInterval * $i),
                    'installment_number' => $i + 1,
                    'installment_amount' => $cashInstallmentAmount,
                    'status' => 'unpaid',
                ]);
            }
        }

        // Commit the transaction
        DB::commit();

        return response()->json(['message' => 'Sale successfully saved with updated exchange status.']);
    } catch (\Exception $e) {
        DB::rollBack(); // Rollback the transaction on error
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


//here exchangestatus is saved on each and every sale that save using exchangesell page 
}