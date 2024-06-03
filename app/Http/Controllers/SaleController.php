<?php

// app/Http/Controllers/SaleController.php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Room;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'customer_name' => 'required|string|max:255',
            'customer_contact' => 'required|string|max:255',
            'customer_email' => 'nullable|email',
            'sale_amount' => 'required|numeric',
            'parking_amount' => 'required|numeric',
        ]);

        // Calculate total amount based on room type
        $room = Room::findOrFail($validatedData['room_id']);
        switch ($room->room_type) {
            case 'Flat':
                $totalAmount = $validatedData['sale_amount'] * $room->total_sq_ft;
                break;
            case 'Shops':
                $totalAmount = $validatedData['sale_amount'] * $room->shop_area;
                break;
            case 'Car parking':
                $totalAmount = $validatedData['sale_amount'] * $room->parking_area;
                break;
            case 'Table space':
                $totalAmount = $validatedData['sale_amount'] * $room->space_area;
                break;
            case 'Kiosk':
                $totalAmount = $validatedData['sale_amount'] * $room->kiosk_area;
                break;
            default:
                $totalAmount = 0; // Default to 0 if the room type is unknown
        }

        // Create a new sale instance with the calculated total amount
        $sale = new Sale();
        $sale->room_id = $validatedData['room_id'];
        $sale->customer_name = $validatedData['customer_name'];
        $sale->customer_contact = $validatedData['customer_contact'];
        $sale->customer_email = $validatedData['customer_email'];
        $sale->sale_amount = $validatedData['sale_amount'];
        $sale->parking_amount = $validatedData['parking_amount'];
        $sale->total_amount = $totalAmount;
        $sale->save();

        // Update the room's status to "sold"
        $room->status = 'sold';
        $room->save();

        return redirect()->route('admin.sales.index');
    }

    public function create()
    {
        // Your existing logic to fetch rooms
        $rooms = Room::all();
    
        // Assuming $page is set to 'create' for the create page
        $page = 'create';
    
        return view('sales.create', compact('rooms', 'page'));
    }
    public function showSales()
    {
        $sales = Sale::all(); // Fetch all sales records from the database
    
        $room = Room::first(); // Assuming you want to fetch a room to pass to the view
    
        $page = 'sales'; // Assuming the page variable is set to 'sales' for the sales page
    
        return view('sales.sales', compact('sales', 'room', 'page'));
    }
    public function softDelete($id)
    {
        // Soft delete the sale
        $sale = Sale::findOrFail($id);
        $sale->delete();
    
        // Update the status of the associated room to 'available'
        $room = Room::findOrFail($sale->room_id);
        $room->status = 'available';
        $room->save();
    
        return redirect()->back();
    }    


    //customers
    public function index()
    {
        $customerNames = Sale::pluck('customer_name')->unique();
        $sales = Sale::all(); // Fetch all sales records
        return view('customers.index', compact('customerNames', 'sales'));
    }
    public function showCustomer($customerName)
{
    // Fetch sales records for the given customer name
    $sales = Sale::with('room.building')
                 ->where('customer_name', $customerName)
                 ->get();

    $page = 'customer_details'; // or any relevant page identifier

    return view('customers.show', compact('sales', 'customerName', 'page'));
}

}
