<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Room;
use App\Models\Installment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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

        $sale->save();

        $room->status = 'sold';
        $room->save();

        // Create installments
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
        $customer = sale::where('customer_name', $customerName)->first();
    
        if (!$customer) {
            abort(404);
        }
    
        // Fetch related installments
    // Fetch related sales records for the customer
    $sales = Sale::where('customer_name', $customerName)->get();

    $page = 'customer';
    // Fetch related installments
    $installments = Installment::where('sale_id', $customer->id)->get();

    $totalPaidInstallments = $installments->where('status', 'paid')->sum('installment_amount');

    $remainingBalanceAfterInstallments = $customer->remaining_balance - $totalPaidInstallments;


    return view('customers.show', compact('customer', 'sales', 'installments','page','remainingBalanceAfterInstallments'));
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
    
}