<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Partner;
use App\Models\Installment;
use Illuminate\Support\Facades\Log;
use App\Models\Sale;

class PartnerController extends Controller
{
    public function create()
    {
        $title = 'Add New Partner';
        $page = 'create-partner'; // Define the page variable
        return view('partners.create', compact('title', 'page'));
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

        $data = [];

        foreach ($partners as $partner) {
            $sales = Sale::where('cash_in_hand_partner_name', $partner->id)
                ->whereNull('deleted_at')
                ->get();

            $partnersData = [];

            foreach ($sales as $sale) {
                $amountReceived = ($sale->in_hand_amount * $sale->cash_in_hand_percent) / 100;

                $partnersData[] = [
                    'sale_id' => $sale->id,
                    'partner_name' => $partner->first_name . ' ' . $partner->last_name,
                    'phone_number' => $partner->phone_number,
                    'address' => $partner->address,
                    'customer_name' => $sale->customer_name,
                    'room_rate' => $sale->room_rate,
                    'cash_in_hand_percent' => $sale->cash_in_hand_percent,
                    'in_hand_amount' => $sale->in_hand_amount,
                    'loan_type' => $sale->loan_type,
                    'percentage' => $sale->cash_in_hand_percent,
                    'amount_received' => $amountReceived
                ];
            }

            $data[] = [
                'partner_id' => $partner->id,
                'partners' => $partnersData
            ];
        }

        $title = 'Cash In Hand';
        $page = 'cash_in_hand';  // Define the current page

        return view('partners.cash_in_hand', compact('data', 'title', 'page'));
    }

    public function markAsPaid(Request $request, Partner $partner)
    {
        $validated = $request->validate([
            'payment_date' => 'required|date',
            'payment_amount' => 'required|numeric|min:0',
        ]);

        $sale = Sale::where('cash_in_hand_partner_name', $partner->id)
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->first();

        if ($sale) {
            $sale->cash_in_hand_paid_amount = $validated['payment_amount'];
            $sale->updated_at = $validated['payment_date'];
            $sale->save();
        }

        return redirect()->route('admin.partners.cash_in_hand')->with('success', 'Payment marked successfully!');
    }

    public function markMultipleAsPaid(Request $request)
    {
        try {
            $installments = $request->input('installments');
            $installmentDates = $request->input('installment_dates');
            $transactionDetails = $request->input('transaction_details');
            $bankDetails = $request->input('bank_details');

            if (is_array($installments)) {
                foreach ($installments as $installmentId) {
                    $installment = Installment::find($installmentId);

                    if ($installment) {
                        $installment->status = 'paid';
                        $installment->installment_date = $installmentDates[$installmentId] ?? $installment->installment_date;
                        $installment->transaction_details = $transactionDetails[$installmentId] ?? $installment->transaction_details;
                        $installment->bank_details = $bankDetails[$installmentId] ?? $installment->bank_details;
                        $installment->save();
                    } else {
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
    public function listPartners()
    {
        $partners = Partner::all(); // Fetch all partners from the database
        $title = 'List of Partners';
        $page = 'List of Partners';
        return view('partners.list', compact('partners', 'title', 'page')); // Pass data to the view

    }
    public function edit($id)
    {
        $partner = Partner::findOrFail($id); // Fetch the partner using the ID
        $title = 'List of Partners';
        $page = 'List of Partners';
        return view('partners.edit', compact('partner', 'title', 'page')); // Return an edit form view
    }
    public function destroy($id)
    {
        $partner = Partner::findOrFail($id); // Fetch the partner using thpe ID
        $partner->delete(); // Delete the partner


        return redirect()->route('admin.partners.list')->with('success', 'Partner deleted successfully.');
    }
    public function update(Request $request, $id)
    {
        $partner = Partner::findOrFail($id);
        $partner->update($request->only(['first_name', 'last_name', 'email', 'phone_number', 'address']));

        return redirect()->route('admin.partners.list')->with('success', 'Partner updated successfully.');
    }
}
