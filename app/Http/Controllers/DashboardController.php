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
        $soldRooms = Room::whereHas('sale')->count();


        // Count specific room types
        $totalShops = Room::where('room_type', 'Shops')->count();
        $totalFlats = Room::where('room_type', 'Flat')->count();
        $totalKiosks = Room::where('room_type', 'Kiosk')->count();
        $totalChairSpaces = Room::where('room_type', 'Chair Space')->count();
        $totalTableSpaces = Room::where('room_type', 'Table Space')->count();

        // Fetch latest sales
        $sales = Sale::orderBy('created_at', 'desc')->take(5)->get();

        // Pluck customer names from sales
        $customerNames = Sale::pluck('customer_name');

        // Calculate total with discount, total sale amount, total GST
        $totalWithDiscount = Sale::sum('total_with_discount');
        $totalSale = Sale::sum('total_amount');
        $totalGst = Sale::sum('total_with_gst');

        // Calculate expected price based on room types
        $ExpectedPrice = Room::sum('expected_carpet_area_price')
            + Room::sum('flat_expected_carpet_area_price')
            + Room::sum('kiosk_expected_price')
            + Room::sum('chair_space_expected_rate')
            + Room::sum('space_expected_price');

        // Initialize arrays to hold expected and sold amount data for each building
        $expectedAmountData = [];
        $soldAmountData = [];

        // Calculate expected amount and sold amount for each building
        foreach ($buildings as $building) {
            // Calculate expected amount (sum of relevant fields from rooms)
            $expectedAmount = Room::where('building_id', $building->id)
                ->sum('expected_carpet_area_price')
                + Room::where('building_id', $building->id)
                ->sum('flat_expected_carpet_area_price')
                + Room::where('building_id', $building->id)
                ->sum('kiosk_expected_price')
                + Room::where('building_id', $building->id)
                ->sum('chair_space_expected_rate')
                + Room::where('building_id', $building->id)
                ->sum('space_expected_price');

            // Calculate sold amount for this building
            $soldAmount = Sale::whereHas('room', function ($query) use ($building) {
                    $query->where('building_id', $building->id);
                })
                ->sum('total_with_discount');

            $expectedAmountData[] = $expectedAmount;
            $soldAmountData[] = $soldAmount;
        }

        // Pass data to the view
        return view('pages.dashboard', compact(
            'buildings', 'sales', 'customerNames',
            'totalCustomers', 'totalShops', 'totalFlats',
            'totalKiosks', 'totalChairSpaces', 'totalTableSpaces',
            'totalRooms', 'ExpectedPrice', 'totalWithDiscount', 'totalSale', 'totalGst',
            'expectedAmountData', 'soldAmountData','soldRooms'
        ));
    }
}
