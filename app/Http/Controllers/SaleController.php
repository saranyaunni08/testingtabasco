<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Room;
use App\Models\Installment;
use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        // Validate the incoming request data
        $validatedData = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_contact' => 'required|string|max:20',
            'area_calculation_type' => 'required|string',
            'sale_amount' => 'required|numeric',
            'calculation_type' => 'nullable|string',
            'parking_rate_per_sq_ft' => 'nullable|numeric',
            'total_sq_ft_for_parking' => 'nullable|numeric',
            'gst_percent' => 'required|numeric',
            'advance_payment' => 'required|string',
            'advance_amount' => 'nullable|numeric',
            'payment_method' => 'nullable|string',
            'transfer_id' => 'nullable|string',
            'cheque_id' => 'nullable|string',
            'last_date' => 'nullable|date',
            'discount_percent' => 'nullable|numeric',
            'installments' => 'required|numeric',
            'installment_date' => 'nullable|date',
            'cash_in_hand_percent' => 'nullable|numeric',
            'in_hand_amount' => 'nullable|numeric',

         
        
            
        ]);

        $room = Room::find($validatedData['room_id']);

        $roomRate = $this->calculateRoomRate($validatedData, $room);
        $parkingAmount = $this->calculateParkingAmount($validatedData);
        $totalAmount = $roomRate + $parkingAmount;
        $amountForGst = $totalAmount - ($validatedData['in_hand_amount'] ?? 0);
        $gstAmount = $amountForGst * ($validatedData['gst_percent'] / 100);
        $totalWithGst = $totalAmount + $gstAmount;
        $totalWithDiscount = isset($validatedData['discount_percent']) ? $totalWithGst - ($totalWithGst * ($validatedData['discount_percent'] / 100)) : $totalWithGst;
        $remainingBalance = $totalWithDiscount - ($validatedData['advance_amount'] ?? 0);

        $sale = new Sale();
        $sale->room_id = $validatedData['room_id'];
        $sale->customer_name = $validatedData['customer_name'];
        $sale->customer_email = $validatedData['customer_email'];
        $sale->customer_contact = $validatedData['customer_contact'];
        $sale->area_calculation_type = $validatedData['area_calculation_type'];
        $sale->sale_amount = $validatedData['sale_amount'];
        $sale->calculation_type = $validatedData['calculation_type'];
        $sale->parking_rate_per_sq_ft = $validatedData['parking_rate_per_sq_ft'];
        $sale->total_sq_ft_for_parking = $validatedData['total_sq_ft_for_parking'];
        $sale->gst_percent = $validatedData['gst_percent'];
        $sale->advance_payment = $validatedData['advance_payment'];
        $sale->advance_amount = $validatedData['advance_amount'];
        $sale->payment_method = $validatedData['payment_method'];
        $sale->transfer_id = $validatedData['transfer_id'];
        $sale->cheque_id = $validatedData['cheque_id'];
        $sale->last_date = $validatedData['last_date'];
        $sale->discount_percent = $validatedData['discount_percent'];
        $sale->installments = $validatedData['installments'];
        $sale->installment_date = $validatedData['installment_date'];
        $sale->cash_in_hand_percent = $validatedData['cash_in_hand_percent'];
        $sale->in_hand_amount = $validatedData['in_hand_amount'];
        $sale->room_rate = $roomRate;
        $sale->total_amount = $totalAmount;
        $sale->parking_amount = $parkingAmount;
        $sale->gst_amount = $gstAmount;
        $sale->total_with_gst = $totalWithGst;
        $sale->total_with_discount = $totalWithDiscount;
        $sale->remaining_balance = $remainingBalance;

        $sale->cuspayment_method = $request->input('cuspayment_method');
        $sale->custransfer_id = $request->input('custransfer_id');
        $sale->cuscheque_id = $request->input('cuscheque_id');
        $sale->status = 'cancel';

        $sale->save();

        $room->status = 'sold';
        $room->save();

        $installmentAmount = $remainingBalance / $validatedData['installments'];
        $installmentDate = Carbon::parse($validatedData['installment_date']);

        for ($i = 0; $i < $validatedData['installments']; $i++) {
            Installment::create([
                'sale_id' => $sale->id,
                'installment_date' => $installmentDate->copy()->addMonths($i),
                'installment_amount' => $installmentAmount,
                'transaction_details' => '',
                'bank_details' => '',
                'status' => 'pending',
            ]);
        }

        return back()->with('success', 'Room sold successfully!');
    }

    protected function calculateRoomRate($validatedData, $room)
    {
        $areaProperties = [
            'Shops' => ['carpet_area', 'build_up_area'],
            'Flat' => ['flat_carpet_area', 'flat_build_up_area'],
            'Table space' => ['space_area', 'space_area'],
            'Chair space' => ['chair_space', 'chair_space']
        ];

        $areaCalculationType = $validatedData['area_calculation_type'];
        $saleAmount = $validatedData['sale_amount'];

        if (array_key_exists($room->room_type, $areaProperties)) {
            $propertyIndex = ($areaCalculationType == 'carpet_area_rate') ? 0 : 1;
            $areaProperty = $areaProperties[$room->room_type][$propertyIndex];

            if (isset($room->$areaProperty)) {
                return $saleAmount * $room->$areaProperty;
            }
        }

        return 0;
    }

    protected function calculateParkingAmount($validatedData)
    {
        $parkingAmount = 0;
        if ($validatedData['calculation_type'] == 'fixed_amount') {
            $parkingAmount = 0;
        } elseif ($validatedData['calculation_type'] == 'rate_per_sq_ft' && 
                  !is_null($validatedData['total_sq_ft_for_parking']) && 
                  !is_null($validatedData['parking_rate_per_sq_ft'])) {
            $parkingAmount = $validatedData['total_sq_ft_for_parking'] * $validatedData['parking_rate_per_sq_ft'];
        }
        return $parkingAmount;
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
    public function showCustomer($customerName)
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
        $building = $room ? $room->building : null; // Fetch the building if the room is not null
    
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
    
        // Set page variable
        $page = 'customer';
    
        return view('customers.show', compact(
            'customer', 
            'sales', 
            'installments', 
            'page', 
            'remainingBalanceAfterInstallments', 
            'room', 
            'emi_amount', 
            'tenure_months', 
            'emi_start_date', 
            'emi_end_date',
            'building' // Pass the building variable to the view
        ));
    }
    

    public function getCalculationType(Request $request) 
    {
        $roomId = $request->input('room_id');
        $type = $request->input('type');

        $roomData = Room::find($roomId);

        if ($type === 'carpet_area_rate') {
            if ($roomData->room_type == 'Shops') {
                $data = ['sqft' => $roomData->carpet_area];
            } elseif ($roomData->room_type == 'Flat') {
                $data = ['sqft' => $roomData->flat_carpet_area];
            } elseif ($roomData->room_type == 'Table space') {
                $data = ['sqft' => $roomData->space_area];
            } elseif ($roomData->room_type == 'Chair space') {
                $data = ['sqft' => $roomData->chair_space];
            }
        } else if ($type === 'build_up_area_rate') {
            if ($roomData->room_type == 'Shops') {
                $data = ['sqft' => $roomData->build_up_area];
            } elseif ($roomData->room_type == 'Flat') {
                $data = ['sqft' => $roomData->flat_build_up_area];
            } elseif ($roomData->room_type == 'Table space') {
                $data = ['sqft' => $roomData->space_area];
            } elseif ($roomData->room_type == 'Chair space') {
                $data = ['sqft' => $roomData->chair_space];
            }
        }

        return response()->json($data);
    }
    public function markAsPaid(Request $request, $installmentId)
    {
        $installment = Installment::findOrFail($installmentId);
    
        $installment->status = 'paid';
        $installment->transaction_details = $request->input('transaction_details');
        $installment->bank_details = $request->input('bank_details');
        $installment->save();
    
        return redirect()->back()->with('success', 'Installment marked as paid.');
    }
    
    public function update(Request $request, $id)
    {
        // Handle the update logic here
        $customer = sale::findOrFail($id);
        $customer->update($request->all());

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
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
        // Example logic to get the building. Adjust as needed.
        $building = Building::first(); // or another method to get the specific building
        
        $cancelledSales = Sale::with('room')->where('status', 'cancelled')->get();
        $page = 'cancelled-sales';
        
        return view('admin.sales.cancelled', compact('cancelledSales', 'page', 'building'));
    }
    
    public function viewCancelledSaleDetails($id)
    {
        // Fetch the sale details by ID with the related room
        $sale = Sale::with('room.building')->find($id);
    
        // Check if sale is found and status is 'cancelled'
        if (!$sale || $sale->status !== 'cancelled') {
            return redirect()->route('admin.sales.cancelled')->withErrors('Sale not found or not cancelled.');
        }
    
        // Fetch the installments related to this sale
        $installments = Installment::where('sale_id', $id)->get();
    
        // Get the related building
        $building = $sale->room ? $sale->room->building : null;
    
        return view('admin.sales.cancelled_details', compact('sale', 'installments', 'building'));
    }
    
}