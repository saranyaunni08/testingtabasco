<?php

// app/Http/Controllers/SaleController.php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Room;
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
            'calculation_type' => 'required|string',
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

        // Fetch the room details
        $room = Room::find($validatedData['room_id']);

        // Calculate the room rate based on area calculation type
        $roomRate = 0;
        if ($validatedData['area_calculation_type'] == 'carpet_area_rate') {
            $roomRate = $validatedData['sale_amount'] * $room->carpet_area;
        } elseif ($validatedData['area_calculation_type'] == 'build_up_area_rate') {
            $roomRate = $validatedData['sale_amount'] * $room->build_up_area;
        }

        // Calculate the parking amount based on calculation type
        $parkingAmount = 0;
        if ($validatedData['calculation_type'] == 'fixed_amount') {
            $parkingAmount = config('master_setting.parking_fixed_rate');
        } elseif ($validatedData['calculation_type'] == 'rate_per_sq_ft' && !is_null($validatedData['total_sq_ft_for_parking']) && !is_null($validatedData['parking_rate_per_sq_ft'])) {
            $parkingAmount = $validatedData['total_sq_ft_for_parking'] * $validatedData['parking_rate_per_sq_ft'];
        }

        // Calculate the total amount including room rate and parking amount
        $totalAmount = $roomRate + $parkingAmount;

        // Add GST to the total amount
        $totalWithGst = $totalAmount + ($totalAmount * ($validatedData['gst_percent'] / 100));

        // Apply discount if provided
        $totalWithDiscount = isset($validatedData['discount_percent']) ? $totalWithGst - ($totalWithGst * ($validatedData['discount_percent'] / 100)) : $totalWithGst;

        // Calculate remaining balance after advance payment
        $remainingBalance = $totalWithDiscount - ($validatedData['advance_amount'] ?? 0);

        // Save the sale to the database
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
        $sale->room_rate = $roomRate; // Add room rate
        $sale->total_amount = $totalAmount; // Add room rate
        $sale->parking_amount = $parkingAmount; // Add parking amount
        $sale->total_with_gst = $totalWithGst; // Add total amount with GST
        $sale->total_with_discount = $totalWithDiscount; // Add total amount after discount
        $sale->remaining_balance = $remainingBalance; // Add remaining balance
        $sale->save();

        // Update room status to 'sold'
        $room->status = 'sold';
        $room->save();

        // Redirect back with a success message
        return back()->with('success', 'Room sold successfully!');
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

        $room = Room::first();
        $page = 'sales';

        return view('sales.sales', compact('sales', 'room', 'page'));
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

    public function index()
    {
        $customerNames = Sale::pluck('customer_name')->unique();
        $sales = Sale::all();
        return view('customers.index', compact('customerNames', 'sales'));
    }

    public function showCustomer($customerName)
    {
        // Fetch sales records for the given customer name
        $sales = Sale::with('room.building')
                     ->where('customer_name', $customerName)
                     ->get();

        // Calculate total amount for each sale and attach room details
        foreach ($sales as $sale) {
            $room = $sale->room;
            $sale->total_amount = $sale->sale_amount + ($sale->parking_amount ?? 0) - ($sale->sale_amount * ($sale->discount_percent / 100));
            $sale->room_details = [
                'room_number' => $room->room_number,
                // Add other room details here as needed
            ];
        }

        $page = 'customer_details';

        return view('customers.show', compact('sales', 'customerName', 'page'));
    }
}
