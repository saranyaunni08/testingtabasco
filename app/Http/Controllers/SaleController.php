<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Room;
use App\Models\building;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SaleController extends Controller
{
    public function store(Request $request)
    {
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
        ]);

        $room = Room::find($validatedData['room_id']);
        $roomRate = $this->calculateRoomRate($validatedData, $room);
        $parkingAmount = $this->calculateParkingAmount($validatedData);

        $totalAmount = $roomRate + $parkingAmount;
        $totalWithGst = $totalAmount + ($totalAmount * ($validatedData['gst_percent'] / 100));
        $totalWithDiscount = isset($validatedData['discount_percent']) ? $totalWithGst - ($totalWithGst * ($validatedData['discount_percent'] / 100)) : $totalWithGst;
        $remainingBalance = $totalWithDiscount - ($validatedData['advance_amount'] ?? 0);

        // Log the calculated values to debug
        Log::info('Room Rate: ' . $roomRate);
        Log::info('Parking Amount: ' . $parkingAmount);
        Log::info('Total Amount: ' . $totalAmount);
        Log::info('Total with GST: ' . $totalWithGst);
        Log::info('Total with Discount: ' . $totalWithDiscount);
        Log::info('Remaining Balance: ' . $remainingBalance);

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
        $sale->room_rate = $roomRate;
        $sale->total_amount = $totalAmount;
        $sale->parking_amount = $parkingAmount;
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
        $roomRate = 0;
        switch ($room->room_type) {
            case 'Shops':
                if ($validatedData['area_calculation_type'] == 'carpet_area_rate') {
                    $roomRate = $validatedData['sale_amount'] * $room->carpet_area;
                } elseif ($validatedData['area_calculation_type'] == 'build_up_area_rate') {
                    $roomRate = $validatedData['sale_amount'] * $room->build_up_area;
                }
                break;
            case 'Flat':
                if ($validatedData['area_calculation_type'] == 'carpet_area_rate') {
                    $roomRate = $validatedData['sale_amount'] * $room->flat_carpet_area;
                } elseif ($validatedData['area_calculation_type'] == 'build_up_area_rate') {
                    $roomRate = $validatedData['sale_amount'] * $room->flat_build_up_area;
                }
                break;
            case 'Table space':
            case 'Chair space':
                if ($validatedData['area_calculation_type'] == 'carpet_area_rate' || $validatedData['area_calculation_type'] == 'build_up_area_rate') {
                    $roomRate = $validatedData['sale_amount'] * $room->space_area;
                }
                break;
        }
        return $roomRate;
    }

    protected function calculateParkingAmount($validatedData)
    {
        $parkingAmount = 0;
        if ($validatedData['calculation_type'] == 'fixed_amount') {
            $parkingAmount = config('master_setting.parking_fixed_rate');
        } elseif ($validatedData['calculation_type'] == 'rate_per_sq_ft' && !is_null($validatedData['total_sq_ft_for_parking']) && !is_null($validatedData['parking_rate_per_sq_ft'])) {
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
        $sales = Sale::where('customer_name', $customerName)->get();

        if ($sales->isEmpty()) {
            abort(404, 'Customer not found.');
        }

        return view('customers.show', compact('sales'));
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
}
