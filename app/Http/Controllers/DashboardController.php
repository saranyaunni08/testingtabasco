<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Building;
use App\Models\sale;
use App\Models\Room;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch all buildings
        $buildings = Building::all();

        // Count total customers
        $totalCustomers = Sale::count();

        // Count total rooms
        $totalRooms = Room::count();

        // Count sold rooms
        $soldRooms = Room::has('sales')->count();

        // Count specific room types
        $totalShops = Room::where('room_type', 'Shops')->count();
        $totalFlats = Room::where('room_type', 'Flat')->count();
        $totalKiosks = Room::where('room_type', 'Kiosk')->count();
        $totalChairSpaces = Room::where('room_type', 'Chair Space')->count();
        $totalTableSpaces = Room::where('room_type', 'Table Space')->count();

        // Fetch latest sales
        $sales = Sale::orderBy('created_at', 'desc')->take(5)->get();

        // Example: Calculate or retrieve expected price
        $ExpectedPrice = 5000; // Replace with your calculation or retrieval logic

        // Example: Calculate or retrieve expected amount data for chart
        $expectedAmountData = [ /* Your expected amount data calculation or retrieval logic here */ ];

        // Example: Retrieve sold amount data for chart (assuming you already have this)
        $soldAmountData = [ /* Your sold amount data retrieval logic here */ ];

        // Return view with data
        return view('pages.dashboard', compact(
            'buildings',
            'totalCustomers',
            'totalRooms',
            'soldRooms',
            'totalShops',
            'totalFlats',
            'totalKiosks',
            'totalChairSpaces',
            'totalTableSpaces',
            'sales',
            'ExpectedPrice',
            'expectedAmountData',
            'soldAmountData'
        ));
    }
}
