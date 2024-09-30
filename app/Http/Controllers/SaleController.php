<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Room;
use App\Models\Installment;
use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class SaleController extends Controller
{

    public function store(Request $request)
    {
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
            'additional_amounts' => 'nullable|string',
            'total_cash_value' => 'nullable|numeric',
            'total_received_amount' => 'nullable|numeric',
            'partners' => 'nullable|string',
            'partner_distribution' => 'nullable|string',
            'other_expenses' => 'nullable|string',
            'remaining_cash_value' => 'nullable|numeric',
            'loan_type' => 'nullable|string',
            'installment_frequency' => 'nullable|string',
            'installment_start_date' => 'nullable|date',
            'number_of_installments' => 'nullable|integer',
            'installment_amount' => 'nullable|numeric',
            'gst_percentage' => 'nullable|numeric|min:0|max:100',
            'gst_amount' => 'nullable|numeric',
            'total_cheque_value_with_gst' => 'nullable|numeric',
            'received_cheque_value' => 'nullable|numeric',
            'balance_amount' => 'nullable|numeric',
        ]);
    
        Sale::create($validatedData); // Ensure your Sale model uses fillable properties or guarded appropriately

        $room = Room::find($request->room_id);
        $room->status = 'sold';
        $room->save();    
        return redirect()->route('admin.sales.index')->with('success', 'Sale recorded successfully!');
    }
    
    protected function getAreaProperty($room, $areaCalculationType)
    {
        $areaProperties = [
            'Shops' => ['carpet_area', 'build_up_area'],
            'Flat' => ['flat_carpet_area', 'flat_build_up_area'],
            'Table space' => ['space_area', 'space_area'],
            'Chair space' => ['chair_space_in_sq', 'chair_space_in_sq'],
            'Kiosk' => ['kiosk_area', 'kiosk_area'],
        ];

        if (array_key_exists($room->room_type, $areaProperties)) {
            $propertyIndex = ($areaCalculationType == 'carpet_area_rate') ? 0 : 1;
            return $areaProperties[$room->room_type][$propertyIndex];
        }

        return null;
    }

    protected function calculateRoomRate($validatedData, $room)
    {
        $areaProperty = $this->getAreaProperty($room, $validatedData['area_calculation_type']);
        return isset($room->$areaProperty) ? $validatedData['sale_amount'] * $room->$areaProperty : 0;
    }

    protected function calculateParkingAmount($validatedData)
    {
        if ($validatedData['calculation_type'] == 'fixed_amount') {
            return 0;
        } elseif ($validatedData['calculation_type'] == 'rate_per_sq_ft' && 
                  !is_null($validatedData['total_sq_ft_for_parking']) && 
                  !is_null($validatedData['parking_rate_per_sq_ft'])) {
            return $validatedData['total_sq_ft_for_parking'] * $validatedData['parking_rate_per_sq_ft'];
        }
        return 0;
    }

    public function create()
    {
        $rooms = Room::all();
        $page = 'create';
        return view('sales.create', compact('rooms', 'page'));
    }

    public function showSales()
    {
        $sales = Sale::all();
        $page = 'sales';
        return view('sales.sales', compact('sales', 'page'));
    }

    public function softDelete($id)
    {
        $sale = Sale::findOrFail($id);
        $sale->delete();

        $room = Room::findOrFail($sale->room_id);
        $room->status = 'available';
        $room->save();

        return redirect()->back();
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $customerNames = Sale::pluck('customer_name')->unique();
        $salesQuery = Sale::query();

        if ($search) {
            $salesQuery->where('customer_name', 'like', '%' . $search . '%');
        }

        $sales = $salesQuery->paginate(10);
        return view('customers.index', compact('customerNames', 'sales', 'search'));
    }

    public function showCustomer($saleId)
    {
        $sale = Sale::with('room', 'installments')->findOrFail($saleId);
        $installments = Installment::where('sale_id', $saleId)->get(); 
        $room = Room::find($sale->room_id);

        $totalPaidInstallments = $installments->where('status', 'paid')->sum('installment_amount');
        $emi_amount = $installments->sum('installment_amount');
        $tenure_months = $installments->count();
        $emi_start_date = $installments->first()->installment_date ?? null;
        $emi_end_date = $installments->last()->installment_date ?? null;

        $remainingBalanceAfterInstallments = $sale->remaining_balance - $totalPaidInstallments;
        $page = 'customer';

        // If the view expects multiple sales, wrap the single sale in a collection
        $sales = collect([$sale]);

        return view('customers.show', compact(
            'sales', 'installments', 'page',
            'remainingBalanceAfterInstallments', 'emi_amount', 'tenure_months',
            'emi_start_date', 'emi_end_date',
            'room',
        ));
    }

    public function getCalculationType(Request $request)
    {
        $roomType = $request->input('room_type');
        $calculationType = $request->input('calculation_type');

        if ($roomType == 'Shops' || $roomType == 'Flat') {
            $type = $calculationType == 'carpet_area_rate' ? 'carpet_area' : 'build_up_area';
        } elseif ($roomType == 'Table space') {
            $type = $calculationType == 'rate_per_sq_ft' ? 'space_area' : 'space_area';
        } elseif ($roomType == 'Chair space') {
            $type = $calculationType == 'rate_per_sq_ft' ? 'chair_space_in_sq' : 'chair_space_in_sq';
        } elseif ($roomType == 'Kiosk') {
            $type = $calculationType == 'rate_per_sq_ft' ? 'kiosk_area' : 'kiosk_area';
        } else {
            $type = null;
        }

        return response()->json(['type' => $type]);
    }

    public function downloadCsv()
    {
        $sales = Sale::all();
        $filename = 'sales_data_' . now()->format('Ymd_His') . '.csv';
        $csvWriter = Writer::createFromFileObject(new \SplTempFileObject());

        $csvWriter->insertOne([
            'ID', 'Customer Name', 'Room ID', 'Sale Amount', 'GST Amount', 'Total Amount', 'Remaining Balance', 'Status'
        ]);

        foreach ($sales as $sale) {
            $csvWriter->insertOne([
                $sale->id, $sale->customer_name, $sale->room_id, $sale->sale_amount, $sale->gst_amount, $sale->total_amount, $sale->remaining_balance, $sale->status
            ]);
        }

        $csvContent = $csvWriter->toString();
        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
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
    public function downloadCustomerDetails($customerName)
    {
        // Fetch customer by name
        $customer = Sale::where('customer_name', $customerName)->first();
    
        if (!$customer) {
            abort(404);
        }
    
        // Fetch related sales records for the customer
        $sales = Sale::where('customer_name', $customerName)->get();
    
        if ($sales->isEmpty()) {
            abort(404);
        }
    
        $room = $sales->first()->room;
    
        // Fetch related installments
        $installments = Installment::whereIn('sale_id', $sales->pluck('id'))->get();
    
        // Calculate total paid installments
        $totalPaidInstallments = $installments->where('status', 'paid')->sum('installment_amount');
    
        // Calculate remaining balance after installments
        $remainingBalanceAfterInstallments = $customer->remaining_balance - $totalPaidInstallments;
    
        // Calculate EMI Amount
        $emi_amount = $installments->sum('installment_amount');
    
        // Calculate tenure (months)
        $tenure_months = $installments->count();
    
        // Get first and last installment dates
        $emi_start_date = $installments->first()->installment_date;
        $emi_end_date = $installments->last()->installment_date;
    
        // Prepare CSV data
        $csvData = [
            ['Loan Details'],
            ['Loan No', $customer->id],
            ['Disb Date', $customer->created_at->format('d-m-Y')],
            ['Cost of Asset', $customer->total_with_discount],
            ['EMI Start Date', $emi_start_date->format('d-m-Y')],
            ['EMI End Date', $emi_end_date->format('d-m-Y')],
            ['EMI Amount', $emi_amount],
            ['Tenure (Months)', $tenure_months],
            ['Asset', $room->room_type],
            ['Loan Amount', $customer->remaining_balance],
            ['Current EMI OS', $remainingBalanceAfterInstallments],
            [],
            ['Installment Details'],
            ['SL No', 'ID', 'Installment Date', 'Amount', 'Transaction Details', 'Bank Details', 'Status'],
        ];
    
        foreach ($installments as $index => $installment) {
            $csvData[] = [
                $index + 1,
                $installment->id,
                $installment->installment_date->format('d-m-Y'),
                $installment->installment_amount,
                $installment->transaction_details,
                $installment->bank_details,
                $installment->status === 'paid' ? 'Paid' : 'Pending'
            ];
        }
    
        // Generate CSV
        $csv = Writer::createFromString('');
        $csv->insertAll($csvData);
    
        // Create a filename for the CSV
        $filename = 'customer_details_' . $customer->id . '.csv';
    
        // Save CSV to storage
        Storage::put('public/' . $filename, $csv->getContent());
    
        // Return CSV download response
        return response()->download(storage_path('app/public/' . $filename))->deleteFileAfterSend(true);
    }

    public function downloadPdf($customerName)
    {

        $customer = Sale::where('customer_name', $customerName)->firstOrFail();
        

        $sales = Sale::where('customer_name', $customerName)->get();
        
        if ($sales->isEmpty()) {
            abort(404);
        }
    
        $room = $sales->first()->room;
    

        $installments = Installment::whereIn('sale_id', $sales->pluck('id'))->get();
    

        $totalPaidInstallments = $installments->where('status', 'paid')->sum('installment_amount');
        $remainingBalanceAfterInstallments = $customer->remaining_balance - $totalPaidInstallments;
        $emi_amount = $installments->sum('installment_amount');
        $tenure_months = $installments->count();
        $emi_start_date = $installments->first()->installment_date;
        $emi_end_date = $installments->last()->installment_date;
    

        $pdf = PDF::loadView('pdf.customer-details', [
            'customer' => $customer,
            'installments' => $installments,
            'emi_start_date' => $emi_start_date,
            'emi_end_date' => $emi_end_date,
            'emi_amount' => $emi_amount,
            'tenure_months' => $tenure_months,
            'remainingBalanceAfterInstallments' => $remainingBalanceAfterInstallments,
            'room' => $room
        ]);
    

        return $pdf->download('customer-details.pdf');
    }
       
    public function downloadInstallmentPdf($id)
    {
        $installment = Installment::find($id);
        $sale = $installment ? Sale::find($installment->sale_id) : null;

        // Extracting customer and room details directly from the sale
        $customer_name = $sale ? $sale->customer_name : 'N/A';
        $customer_email = $sale ? $sale->customer_email : 'N/A';
        $customer_contact = $sale ? $sale->customer_contact : 'N/A';

        // Fetch all installments for the given sale_id
        $installments = $sale ? Installment::where('sale_id', $sale->id)->get() : collect();

        // Determine the EMI start and end dates
        $emi_start_date = $installments->min('installment_date') ?? 'N/A';
        $emi_end_date = $installments->max('installment_date') ?? 'N/A';

        $emi_amount = $installment ? $installment->installment_amount : 0;
        $tenure_months = $installments->count();

        // Remaining balance calculation
        $total_paid_installments = $installments->where('status', 'paid')->sum('installment_amount');
        $remaining_balance_after_installments = $sale ? $sale->remaining_balance - $total_paid_installments : 0;

        $data = [
            'installment' => $installment,
            'customer_name' => $customer_name,
            'customer_email' => $customer_email,
            'customer_contact' => $customer_contact,
            'sale' => $sale,
            'emi_start_date' => $emi_start_date,
            'emi_end_date' => $emi_end_date,
            'emi_amount' => $emi_amount,
            'tenure_months' => $tenure_months,
            'remainingBalanceAfterInstallments' => $remaining_balance_after_installments,
            'room' => $sale ? $sale->room : null
        ];

        $pdf = PDF::loadView('pdf.installment_detail', $data);
        return $pdf->download('installment_detail.pdf');
    }

    public function cancelSale(Request $request)
    {
        $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'fine_amount' => 'required|numeric',
            'payment_method' => 'required|in:cash,bank,cheque',
            'bank_id' => 'nullable|required_if:payment_method,bank',
            'cheque_id' => 'nullable|required_if:payment_method,cheque',
        ]);

        // Find the sale record
        $sale = Sale::findOrFail($request->sale_id);
        $sale->cancellation_fine_amount = $request->fine_amount;
        $sale->cancellation_payment_method = $request->payment_method;

        // Set the payment details
        if ($request->payment_method === 'bank') {
            $sale->cancellation_bank_id = $request->bank_id;
            $sale->cancellation_cheque_id = null;
        } elseif ($request->payment_method === 'cheque') {
            $sale->cancellation_cheque_id = $request->cheque_id;
            $sale->cancellation_bank_id = null;
        } else {
            $sale->cancellation_bank_id = null;
            $sale->cancellation_cheque_id = null;
        }

        // Update the status of the sale
        $sale->status = 'cancelled';
        $sale->save();

        // Update the room status to "available"
        $room = Room::findOrFail($sale->room_id);
        $room->status = 'available';
        $room->save();

        return redirect()->back()->with('success', 'Sale has been cancelled successfully and the room status has been updated.');
    }

    public function listCancelledSales()
    {
        $building = Building::first(); 
        
        $cancelledSales = Sale::with('room')->where('status', 'cancelled')->get();
        $page = 'cancelled-sales';
        $rooms = Room::all();


        
        return view('admin.sales.cancelled', compact('cancelledSales', 'page', 'building','rooms'));
    }
    public function viewCancelledSaleDetails($id)
    {
        // Fetch the sale details by ID with the related room and installments
        $sale = Sale::with(['room.building', 'installments'])->find($id);
    
        // Check if sale is found and status is 'cancelled'
        if (!$sale || $sale->status !== 'cancelled') {
            return redirect()->route('admin.sales.cancelled')->withErrors('Sale not found or not cancelled.');
        }
    
        // Fetch the installments related to this sale
        $installments = Installment::where('sale_id', $id)->get();
    
        // Calculate the total tenure as the count of installments
        $totalTenureMonths = $installments->count();
    
        // Calculate the remaining tenure by counting only pending installments
        $pendingInstallments = $installments->where('status', 'pending');
        $remainingTenureMonths = $pendingInstallments->count();
    
        // Calculate the remaining balance by summing up the amounts of pending installments
        $remainingBalance = $pendingInstallments->sum('installment_amount');
    
        // Calculate the total received amount by summing up the amounts of paid installments
        $receivedAmount = $installments->where('status', 'paid')->sum('installment_amount');
    
        // Fetch the first and last installment dates
        $firstInstallment = $installments->sortBy('installment_date')->first();
        $lastInstallment = $installments->sortByDesc('installment_date')->first();
    
        $firstInstallmentDate = $firstInstallment ? $firstInstallment->installment_date : null;
        $lastInstallmentDate = $lastInstallment ? $lastInstallment->installment_date : null;
    
        // Get the related building
        $building = $sale->room ? $sale->room->building : null;
    
        // Fetch the cancellation fine amount
        $cancellationFineAmount = $sale->cancellation_fine_amount;
    
        // Calculate the amount to be paid back
        $amountToPayBack = $receivedAmount + $sale->advance_amount + $sale->cash_in_hand_paid_amount - $cancellationFineAmount;
    
        return view('admin.sales.cancelled_details', compact('sale', 'installments', 'building', 'firstInstallment', 'firstInstallmentDate', 'lastInstallmentDate', 'totalTenureMonths', 'remainingTenureMonths', 'remainingBalance', 'receivedAmount', 'cancellationFineAmount', 'amountToPayBack'));
    }
    
}