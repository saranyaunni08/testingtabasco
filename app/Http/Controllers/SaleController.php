<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Installment;

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
        $sales = Sale::where('customer_name', $customerName)
            ->with(['room.building'])
            ->get();
        $page = 'customer';
        $sale = $sales->first();

        foreach ($sales as $sale) {
            $installment = $sale->installments()->first();

            if ($installment) {
                $installmentDate = $installment->installment_date;
                $installmentAmount = $sale->remaining_balance / $sale->installments;
            } else {
                $installmentDate = null;
                $installmentAmount = null;
            }

            $sale->installmentDate = $installmentDate;
            $sale->installmentAmount = $installmentAmount;
        }

        return view('customers.show', compact('sales', 'sale', 'page'));
    }
    
    function getCalculationType(Request $request) {
        $roomId = $request->input('room_id');
        $type = $request->input('type');

        $roomData = Room::find($roomId);

        if($type === 'carpet_area_rate') {
            if($roomData->room_type == 'Shops'){
                $data = ['sqft' => $roomData->carpet_area];
            }elseif($roomData->room_type ==  'Flat') {
                $data = ['sqft' => $roomData->flat_carpet_area];
            }elseif($roomData->room_type ==  'Table space') {
                $data = ['sqft' => $roomData->space_area];
            }elseif($roomData->room_type ==  'Kiosk') {
                $data = ['sqft' => $roomData->kiosk_area];
            }else {
                $data = ['sqft' => $roomData->chair_space_in_sq];
            }

            return response()->json($data);
        }else {
            if($roomData->room_type ==  'Shops'){
                $data = ['sqft' => $roomData->build_up_area];
            }elseif($roomData->room_type ==  'Flat') {
                $data = ['sqft' => $roomData->flat_build_up_area];
            }elseif($roomData->room_type ==  'Table space') {
                $data = ['sqft' => $roomData->space_area];
            }elseif($roomData->room_type ==  'Kiosk') {
                $data = ['sqft' => $roomData->kiosk_area];
            }else {
                $data = ['sqft' => $roomData->chair_space_in_sq];
            }

            return response()->json($data);
        }
    }

  
    
    public function totalCustomers($buildingId)
    {
        $sales = Sale::with('room')
            ->whereHas('room', function ($query) use ($buildingId) {
                $query->where('building_id', $buildingId);
            })
            ->get();
    
        $title = "Total Customers";
        $page = "total-customers";
    
        return view('customers.total_customers', compact('sales', 'title', 'page'));
    }
    public function update(Request $request, Sale $sale)
    {

        $validatedData = $request->validate([
            'calculation_type' => 'required|string', 
            'parking_rate_per_sq_ft' => 'required|numeric',
            'total_sq_ft_for_parking' => 'required|numeric',

        ]);
    

        $sale->calculation_type = $request->input('calculation_type');
        $sale->parking_rate_per_sq_ft = $request->input('parking_rate_per_sq_ft');
        $sale->total_sq_ft_for_parking = $request->input('total_sq_ft_for_parking');
    

        $sale->save();
    

        return redirect()->back()->with('success', 'Sale updated successfully.');
    }
    
    public function markPaid(Request $request, Sale $sale)
    {
        Log::info('Attempting to mark installment as paid.');

        $validatedData = $request->validate([
            'installment_date' => 'required|date_format:Y-m-d',
            'installment_amount' => 'required|numeric',
            'transaction_details' => 'required|string',
            'bank_details' => 'required|string',
        ]);
        
        
    
        // Create a new installment record
        $installment = new Installment();
        $installment->sale_id = $sale->id;
        $installment->installment_date = $validatedData['installment_date'];
        $installment->installment_amount = $validatedData['installment_amount'];
        $installment->transaction_details = $validatedData['transaction_details'];
        $installment->bank_details = $validatedData['bank_details'];
        $installment->status = 'Paid'; // Assuming this updates the sale status
        $installment->save();
    
        // Optionally, you can update the sale status here as well
        $sale->update(['status' => 'Paid']);
    
        // Redirect back with success message
        return redirect()->back()->with('success', 'Installment marked as paid successfully.');
    }
}
