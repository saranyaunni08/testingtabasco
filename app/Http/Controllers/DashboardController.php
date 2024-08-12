<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Building;
use App\Models\Sale;
use App\Models\Room;

class DashboardController extends Controller
{
    public function index()
    {
        // Get all buildings
        $buildings = Building::all();

        // Calculate expected and sold amounts for each building
        $expectedAmountData = [];
        $soldAmountData = [];

        foreach ($buildings as $building) {
            $expectedAmountData[] = $building->rooms->sum('expected_amount');
            $soldAmountData[] = Sale::whereHas('room', function($query) use ($building) {
                $query->where('building_id', $building->id);
            })->sum('total_with_discount');
        }

        // Calculate the total expected price from the rooms table
        $expected_super_buildup_area_price = Room::sum('expected_super_buildup_area_price');
        $flat_expected_super_buildup_area_price = Room::sum('flat_expected_super_buildup_area_price');
        $space_expected_price = Room::sum('space_expected_price');
        $kiosk_expected_price = Room::sum('kiosk_expected_price');
        $chair_space_expected_rate = Room::sum('chair_space_expected_rate');

        $expectedPrice = $expected_super_buildup_area_price 
                        + $flat_expected_super_buildup_area_price 
                        + $space_expected_price 
                        + $kiosk_expected_price 
                        + $chair_space_expected_rate;

        return view('pages.dashboard', [
            'buildings' => $buildings,
            'totalCustomers' => Sale::distinct('customer_name')->count(),
            'totalRooms' => Room::count(),
            'soldRooms' => Sale::count(),
            'totalShops' => Room::where('room_type', 'Shops')->count(),
            'totalFlats' => Room::where('room_type', 'Flat')->count(),
            'totalKiosks' => Room::where('room_type', 'Kiosk')->count(),
            'totalChairSpaces' => Room::where('room_type', 'Chair Space')->count(),
            'totalTableSpaces' => Room::where('room_type', 'Table Space')->count(),
            'sales' => Sale::latest()->take(5)->get(),
            'ExpectedPrice' => $expectedPrice,
            'expectedAmountData' => $expectedAmountData,
            'soldAmountData' => $soldAmountData,
            'page' => 'dashboard' ,
        ]);
    }
}