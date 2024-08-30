<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Partner;
use App\Models\Installment;
Use Illuminate\Support\Facades\Log;

use App\Models\Sale;

class PartnerController extends Controller
{
    public function create()
    {
        $title = 'Add New Partner';

        return view('partners.create', compact('title'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:partners,email',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|max:10',
            'nationality' => 'nullable|string|max:100',
            'identification_number' => 'nullable|string|max:100',
        ]);

        Partner::create($validated);

        return redirect()->route('admin.partners.create')->with('success', 'Partner added successfully!');
    }
    public function cashInHand()
    {
        // Fetch all partners and their associated sales data
        $partners = Partner::select('partners.id', 'partners.first_name', 'partners.last_name')
            ->leftJoin('sales', 'partners.id', '=', 'sales.cash_in_hand_partner_name')
            ->selectRaw('
                MAX(sales.in_hand_amount) as max_in_hand_amount,
                SUM(CASE WHEN sales.deleted_at IS NULL THEN sales.cash_in_hand_paid_amount ELSE 0 END) as total_cash_in_hand,
                SUM(CASE WHEN sales.deleted_at IS NULL THEN sales.remaining_balance ELSE 0 END) as total_amount_due,
                MAX(sales.cash_in_hand_paid_amount) as max_cash_in_hand_paid_amount,
                MAX(sales.created_at) as latest_sales_date
            ')
            ->groupBy('partners.id', 'partners.first_name', 'partners.last_name')
            ->get();
    
        // Define the title
        $title = 'Cash In Hand';
    
        // Pass the partners and title to the view
        return view('partners.cash_in_hand', compact('partners', 'title'));
    }
    
    public function markAsPaid(Request $request, Partner $partner)
    {
        // Validate the request
        $validated = $request->validate([
            'payment_date' => 'required|date',
            'payment_amount' => 'required|numeric|min:0',
        ]);

        // Find the latest sale related to this partner
        $sale = Sale::where('cash_in_hand_partner_name', $partner->id)
                    ->whereNull('deleted_at')
                    ->orderBy('created_at', 'desc')
                    ->first();

        if ($sale) {
            // Update the sale with the payment amount and date
            $sale->cash_in_hand_paid_amount = $validated['payment_amount'];
            $sale->updated_at = $validated['payment_date'];
            $sale->save();
        }

        // Redirect with success message
        return redirect()->route('admin.partners.cash_in_hand')->with('success', 'Payment marked successfully!');
    }

    public function markMultipleAsPaid(Request $request)
    {
        try {
            $installments = $request->input('installments');
            $installmentDates = $request->input('installment_dates');
            $transactionDetails = $request->input('transaction_details');
            $bankDetails = $request->input('bank_details');
    
            // Validate that installments is an array
            if (is_array($installments)) {
                foreach ($installments as $installmentId) {
                    $installment = Installment::find($installmentId);
    
                    if ($installment) {
                        // Update installment details
                        $installment->status = 'paid';
                        $installment->installment_date = $installmentDates[$installmentId] ?? $installment->installment_date;
                        $installment->transaction_details = $transactionDetails[$installmentId] ?? $installment->transaction_details;
                        $installment->bank_details = $bankDetails[$installmentId] ?? $installment->bank_details;
                        $installment->save();
                    } else {
                        // Log or handle the case where 'id' is missing
                        Log::warning('Installment data missing or invalid', ['id' => $installmentId]);
                    }
                }
    
                return redirect()->back()->with('success', 'Selected installments marked as paid.');
            }
        } catch (\Exception $e) {
            Log::error('Error marking installments as paid: ' . $e->getMessage());
        }
    
        return redirect()->back()->with('error', 'No installments selected.');
    }
}
