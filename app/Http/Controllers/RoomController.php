<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\Building;
use App\Models\Sale;
use App\Models\Installment;
use App\Models\partner;
use App\Models\RoomType;
use App\Models\Parking;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;




class RoomController extends Controller
{ 
        
    public function index(Request $request)
    {
        $building_id = $request->input('building_id');
        $building = Building::findOrFail($building_id);
        $flats = Room::where('building_id', $building_id)->where('room_type', 'flat')->get();
        $flatTimeSeries = [];

        foreach ($flats as $flat) {
            $flatTimeSeries[] = [
                'time' => $flat->created_at->format('Y-m-d'), // Assuming 'created_at' is the time reference
                'expected' => $flat->expected_super_buildup_area_price, // Example field for expected amount
                'sold' => optional($flat->sale)->total_amount, // Assuming a 'sale' relationship on the room model
            ];
        }

        $rooms = Room::where('building_id', $building->id)->paginate(10);

        // Filter rooms by type
        $flatRooms = $rooms->filter(fn($room) => $room->room_type === 'Flat');
        $ShopRooms = $rooms->filter(fn($room) => $room->room_type === 'Shops');
        $TableRooms = $rooms->filter(fn($room) => $room->room_type === 'Table space');
        $KioskRooms = $rooms->filter(fn($room) => $room->room_type === 'Kiosk');
        $ChairRooms = $rooms->filter(fn($room) => $room->room_type === 'Chair space');

        // Calculate for Flats
        $soldAmount = Sale::whereIn('room_id', $flatRooms->pluck('id'))->sum('total_amount');
        $expectedAmount = $flatRooms->sum('flat_expected_super_buildup_area_price');
        $totalBuildUpArea = $flatRooms->sum('flat_build_up_area');
        $soldBuildUpArea = $flatRooms->where('status', 'sold')->sum('flat_build_up_area');
        $allFlatsSold = $flatRooms->where('status', 'available')->isEmpty();
        $profitOrLoss = $soldAmount - $expectedAmount;
        $profitOrLossText = ($profitOrLoss > 0) ? 'profit' : 'loss';
        $profitOrLossColor = ($profitOrLoss > 0) ? 'green' : 'red';

        // Calculate for Shops
        $soldShopAmount = Sale::whereIn('room_id', $ShopRooms->pluck('id'))->sum('total_amount');
        $expectedAmountShop = $ShopRooms->sum('expected_super_buildup_area_price');
        $totalShopBuildUpArea = $ShopRooms->sum('build_up_area');
        $soldShopBuildUpArea = $ShopRooms->where('status', 'sold')->sum('build_up_area');
        $allShopsSold = $ShopRooms->where('status', 'available')->isEmpty();
        $profitOrLossShop = $soldShopAmount - $expectedAmountShop;
        $profitOrLossTextShop = ($profitOrLossShop > 0) ? 'profit' : 'loss';
        $profitOrLossColorShop = ($profitOrLossShop > 0) ? 'green' : 'red';

        // Calculate for Table Space
        $soldTableAmount = Sale::whereIn('room_id', $TableRooms->pluck('id'))->sum('total_amount');
        $expectedAmountTable = $TableRooms->sum('space_expected_price');
        $totalTableBuildUpArea = $TableRooms->sum('space_area');
        $soldTableBuildUpArea = $TableRooms->where('status', 'sold')->sum('space_area');
        $allTableSold = $TableRooms->where('status', 'available')->isEmpty();
        $profitOrLossTable = $soldTableAmount - $expectedAmountTable;
        $profitOrLossTextTable = ($profitOrLossTable > 0) ? 'profit' : 'loss';
        $profitOrLossColorTable = ($profitOrLossTable > 0) ? 'green' : 'red';

        // Calculate for Kiosk
        $soldKioskAmount = Sale::whereIn('room_id', $KioskRooms->pluck('id'))->sum('total_amount');
        $expectedAmountKiosk = $KioskRooms->sum('kiosk_expected_price');
        $totalKioskBuildUpArea = $KioskRooms->sum('kiosk_area');
        $soldKioskBuildUpArea = $KioskRooms->where('status', 'sold')->sum('kiosk_area');
        $allKioskSold = $KioskRooms->where('status', 'available')->isEmpty();
        $profitOrLossKiosk = $soldKioskAmount - $expectedAmountKiosk;
        $profitOrLossTextKiosk = ($profitOrLossKiosk > 0) ? 'profit' : 'loss';
        $profitOrLossColorKiosk = ($profitOrLossKiosk > 0) ? 'green' : 'red';

        // Calculate for Chair Space
        $soldChairAmount = Sale::whereIn('room_id', $ChairRooms->pluck('id'))->sum('total_amount');
        $expectedAmountChair = $ChairRooms->sum('chair_space_expected_rate');
        $totalChairBuildUpArea = $ChairRooms->sum('chair_space_in_sq');
        $soldChairBuildUpArea = $ChairRooms->where('status', 'sold')->sum('chair_space_in_sq');
        $allChairSold = $ChairRooms->where('status', 'available')->isEmpty();
        $profitOrLossChair = $soldChairAmount - $expectedAmountChair;
        $profitOrLossTextChair = ($profitOrLossChair > 0) ? 'profit' : 'loss';
        $profitOrLossColorChair = ($profitOrLossChair > 0) ? 'green' : 'red';

        // Room statistics
        $roomStats = [
            'Flat Expected Amount' => [
                'count' => $flatRooms->count(),
                'total' => $expectedAmount,
                'totalBuildUpArea' => $totalBuildUpArea,
                'soldBuildUpArea' => $soldBuildUpArea,
                'soldAmount' => $soldAmount,
                'allFlatsSold' => $allFlatsSold,
                'profitOrLoss' => $profitOrLoss,
                'profitOrLossText' => $profitOrLossText,
                'profitOrLossColor' => $profitOrLossColor,
            ],
            'Shops Expected Amount' => [
                'count' => $ShopRooms->count(),
                'total' => $expectedAmountShop,
                'totalShopBuildUpArea' => $totalShopBuildUpArea,
                'soldShopBuildUpArea' => $soldShopBuildUpArea,
                'soldShopAmount' => $soldShopAmount,
                'allShopsSold' => $allShopsSold,
                'profitOrLossShop' => $profitOrLossShop,
                'profitOrLossTextShop' => $profitOrLossTextShop,
                'profitOrLossColorShop' => $profitOrLossColorShop,
            ],
            'Table space Expected Amount' => [
                'count' => $TableRooms->count(),
                'total' => $expectedAmountTable,
                'totalTableBuildUpArea' => $totalTableBuildUpArea,
                'soldTableBuildUpArea' => $soldTableBuildUpArea,
                'soldTableAmount' => $soldTableAmount,
                'allTableSold' => $allTableSold,
                'profitOrLossTable' => $profitOrLossTable,
                'profitOrLossTextTable' => $profitOrLossTextTable,
                'profitOrLossColorTable' => $profitOrLossColorTable,
            ],
            'Kiosk Expected Amount' => [
                'count' => $KioskRooms->count(),
                'total' => $expectedAmountKiosk,
                'totalKioskBuildUpArea' => $totalKioskBuildUpArea,
                'soldKioskBuildUpArea' => $soldKioskBuildUpArea,
                'soldKioskAmount' => $soldKioskAmount,
                'allKioskSold' => $allKioskSold,
                'profitOrLossKiosk' => $profitOrLossKiosk,
                'profitOrLossTextKiosk' => $profitOrLossTextKiosk,
                'profitOrLossColorKiosk' => $profitOrLossColorKiosk,
            ],
            'Chair space Expected Amount' => [
                'count' => $ChairRooms->count(),
                'total' => $expectedAmountChair,
                'totalChairBuildUpArea' => $totalChairBuildUpArea,
                'soldChairBuildUpArea' => $soldChairBuildUpArea,
                'soldChairAmount' => $soldChairAmount,
                'allChairSold' => $allChairSold,
                'profitOrLossChair' => $profitOrLossChair,
                'profitOrLossTextChair' => $profitOrLossTextChair,
                'profitOrLossColorChair' => $profitOrLossColorChair,
            ],
        ];

        // Calculate totals
        $totalExpectedAmount = array_sum(array_column($roomStats, 'total'));

        $soldAmountData = [
            'Flat Expected Amount' => $flatRooms->where('status', 'sold')->sum('flat_expected_super_buildup_area_price'),
            'Shops Expected Amount' => $ShopRooms->where('status', 'sold')->sum('expected_super_buildup_area_price'),
            'Table space Expected Amount' => $TableRooms->where('status', 'sold')->sum('space_expected_price'),
            'Kiosk Expected Amount' => $KioskRooms->where('status', 'sold')->sum('kiosk_expected_price'),
            'Chair space Expected Amount' => $ChairRooms->where('status', 'sold')->sum('chair_space_expected_rate'),
        ];

        $totalFlats = $roomStats['Flat Expected Amount']['count'];
        $totalShops = $roomStats['Shops Expected Amount']['count'];
        $totalTableSpaces = $roomStats['Table space Expected Amount']['count'];
        $totalKiosks = $roomStats['Kiosk Expected Amount']['count'];
        $totalChairSpaces = $roomStats['Chair space Expected Amount']['count'];

        // Building data
        $buildings = Building::all();

        // Expected price data
        $expectedPriceData = [
            'flats' => $roomStats['Flat Expected Amount']['total'],
            'shops' => $roomStats['Shops Expected Amount']['total'],
            'table_space' => $roomStats['Table space Expected Amount']['total'],
            'kiosk' => $roomStats['Kiosk Expected Amount']['total'],
            'chair_space' => $roomStats['Chair space Expected Amount']['total'],
        ];

        $roomIds = Room::where('building_id', $building_id)->pluck('id');
        $totalSoldAmount = Sale::whereIn('room_id', $roomIds)->sum('total_amount');

        // Total Build-Up Area
        $allTotalBuildUpArea = array_sum([
            $totalBuildUpArea,
            $totalShopBuildUpArea,
            $totalTableBuildUpArea,
            $totalKioskBuildUpArea,
            $totalChairBuildUpArea,
        ]);

        // Sold Build-Up Area
        $totalSoldBuildUpArea = array_sum([
            $soldBuildUpArea,
            $soldShopBuildUpArea,
            $soldTableBuildUpArea,
            $soldKioskBuildUpArea,
            $soldChairBuildUpArea,
        ]);

        // Balance Build-Up Area
        $totalBalanceBuildUpArea = $allTotalBuildUpArea - $totalSoldBuildUpArea;
        $page = 'rooms';


        return view('rooms.show', compact(
            'rooms',
            'building',
            'page',
            'building_id',
            'roomStats',
            'totalFlats',
            'totalShops',
            'totalTableSpaces',
            'totalKiosks',
            'totalChairSpaces',
            'soldAmountData',
            'expectedPriceData',
            'buildings',
            'totalExpectedAmount',
            'totalShopBuildUpArea',
            'soldShopBuildUpArea',
            'totalTableBuildUpArea',
            'soldTableBuildUpArea',
            'soldTableAmount',
            'allShopsSold',
            'totalKioskBuildUpArea',
            'soldKioskBuildUpArea',
            'soldKioskAmount',
            'allKioskSold',
            'totalChairBuildUpArea',
            'soldChairBuildUpArea',
            'soldChairAmount',
            'allChairSold',
            'flatTimeSeries',
            
            'profitOrLossShop',
            'profitOrLossTextShop',
            'profitOrLossColorShop',

            'profitOrLossTable',
            'profitOrLossTextTable',
            'profitOrLossColorTable',

            'profitOrLossKiosk',
            'profitOrLossTextKiosk',
            'profitOrLossColorKiosk',

            'profitOrLossChair',
            'profitOrLossTextChair',
            'profitOrLossColorChair',


            'allTotalBuildUpArea',
            'totalSoldBuildUpArea',
            'totalBalanceBuildUpArea',
            'totalSoldAmount',


        ));
    }
    public function create(Request $request)
    {
        $building_id = $request->building_id;
        $room_type = $request->room_type;
        $roomTypes = RoomType::all();
        $building = Building::findOrFail($building_id);

        // Fetch room types from the database

        // Sum the relevant fields from the rooms table for the specified building
        $roomSums = Room::where('building_id', $building_id)
                        ->selectRaw('SUM(build_up_area) as total_build_up_area')
                        ->selectRaw('SUM(flat_build_up_area) as total_flat_build_up_area')
                        ->selectRaw('SUM(space_area) as total_space_area')
                        ->selectRaw('SUM(kiosk_area) as total_kiosk_area')
                        ->selectRaw('SUM(chair_space_in_sq) as total_chair_space_in_sq')
                        ->first();

        // Extract the summed values
        $total_build_up_area = $roomSums->total_build_up_area ?? 0;
        $total_flat_build_up_area = $roomSums->total_flat_build_up_area ?? 0;
        $total_space_area = $roomSums->total_space_area ?? 0;
        $total_kiosk_area = $roomSums->total_kiosk_area ?? 0;
        $total_chair_space_in_sq = $roomSums->total_chair_space_in_sq ?? 0;

        // Calculate total area price
        $total_area_price = $total_flat_build_up_area + $total_build_up_area + $total_space_area + $total_kiosk_area + $total_chair_space_in_sq;
        $super_build_up_area = $building->super_built_up_area;
        $result = $super_build_up_area - $total_area_price;

        // Calculate total carpet area
        $carpet_area = $request->input('carpet_area', 0);
        $flat_carpet_area = $request->input('flat_carpet_area', 0);
        $total_carpet_area = $flat_carpet_area + $carpet_area + $total_space_area + $total_kiosk_area + $total_chair_space_in_sq;
        $building_carpet_area = $building->carpet_area;
        $result_carpet = $building_carpet_area - $total_carpet_area;

        // Pass room types to the view
        return view('rooms.create', compact('building_id', 'building', 'room_type', 'result', 'result_carpet', 'roomTypes'));
    }
    public function store(Request $request)
    {


        // Validate the incoming data
        $validatedData = $request->validate([
            'room_number' => 'required|string',
            'room_floor' => 'nullable|string',
            'room_type' => 'required|string',
            'flat_build_up_area' => 'nullable|numeric',
            'flat_carpet_area' => 'nullable|numeric',
            'flat_super_build_up_price' => 'nullable|numeric',
            'flat_carpet_area_price' => 'nullable|numeric',
            'build_up_area' => 'nullable|numeric',
            'carpet_area' => 'nullable|numeric',
            'flat_rate' => 'nullable|numeric',
            'super_build_up_price' => 'nullable|numeric',
            'carpet_area_price' => 'nullable|numeric',
            'shop_number' => 'nullable|string',
            'shop_type' => 'nullable|string',
            'shop_area' => 'nullable|numeric',
            'shop_rate' => 'nullable|numeric',
            'shop_rental_period' => 'nullable|string',
            'parking_number' => 'nullable|string',
            'parking_type' => 'nullable|string',
            'parking_area' => 'nullable|numeric',
            'parking_rate' => 'nullable|numeric',
            'space_name' => 'nullable|string',
            'space_type' => 'nullable|string',
            'space_area' => 'nullable|numeric',
            'space_rate' => 'nullable|numeric',
            'space_expected_price' => 'nullable|numeric',
            'kiosk_name' => 'nullable|string',
            'kiosk_type' => 'nullable|string',
            'kiosk_area' => 'nullable|numeric',
            'kiosk_rate' => 'nullable|numeric',
            'kiosk_expected_price' => 'nullable|numeric',
            'chair_name' => 'nullable|string',
            'chair_type' => 'nullable|string',
            'chair_material' => 'nullable|string',
            'chair_price' => 'nullable|numeric',
            'building_id' => 'required|exists:buildings,id',
            'flat_model' => 'nullable|string',
            'sale_amount' => 'nullable|numeric',
            'chair_space_in_sq' => 'nullable|string',
            'chair_space_rate' => 'nullable|string',
            'chair_space_expected_rate' => 'nullable|string',
            'custom_name' => 'nullable|string',
            'custom_type' => 'nullable|string',
            'custom_area' => 'nullable|numeric',
            'custom_rate' => 'nullable|numeric',
            'parking_floor' => 'nullable|string',
            'parking_slot_id' => 'nullable|exists:parkings,id',
            'parking_amount' => 'nullable|numeric', // New field
            'parking_amount_cheque' => 'nullable|numeric',

        ]);
        // dd($request->all());

        // Calculate expected rates
        $expected_custom_rate = isset($validatedData['custom_area']) && isset($validatedData['custom_rate'])
            ? $validatedData['custom_area'] * $validatedData['custom_rate']
            : null;
    
        $expected_carpet_area_price = isset($validatedData['carpet_area'], $validatedData['carpet_area_price'])
            ? $validatedData['carpet_area_price'] * $validatedData['carpet_area']
            : null;
    
        $expected_super_buildup_area_price = isset($validatedData['build_up_area'], $validatedData['super_build_up_price'])
            ? $validatedData['build_up_area'] * $validatedData['super_build_up_price']
            : null;
    
        $flat_expected_carpet_area_price = isset($validatedData['flat_carpet_area'], $validatedData['flat_carpet_area_price'])
            ? $validatedData['flat_carpet_area_price'] * $validatedData['flat_carpet_area']
            : null;
    
        $flat_expected_super_buildup_area_price = isset($validatedData['flat_build_up_area'], $validatedData['flat_super_build_up_price'])
            ? $validatedData['flat_build_up_area'] * $validatedData['flat_super_build_up_price']
            : null;
    
        $space_expected_price = isset($validatedData['space_area'], $validatedData['space_rate'])
            ? $validatedData['space_area'] * $validatedData['space_rate']
            : null;
    
        $kiosk_expected_price = isset($validatedData['kiosk_area'], $validatedData['kiosk_rate'])
            ? $validatedData['kiosk_area'] * $validatedData['kiosk_rate']
            : null;
    
        $chair_space_expected_rate = isset($validatedData['chair_space_in_sq'], $validatedData['chair_space_rate'])
            ? $validatedData['chair_space_in_sq'] * $validatedData['chair_space_rate']
            : null;
    
        // Save room data
        $room = new Room();
        $room->fill($validatedData);
        $room->expected_carpet_area_price = $expected_carpet_area_price;
        $room->expected_super_buildup_area_price = $expected_super_buildup_area_price;
        $room->flat_expected_carpet_area_price = $flat_expected_carpet_area_price;
        $room->flat_expected_super_buildup_area_price = $flat_expected_super_buildup_area_price;
        $room->space_expected_price = $space_expected_price;
        $room->kiosk_expected_price = $kiosk_expected_price;
        $room->chair_space_expected_rate = $chair_space_expected_rate;
    
        if (!in_array($validatedData['room_type'], ['Flat', 'Shops', 'Table space', 'Kiosk', 'Chair space'])) {
            $room->custom_name = $validatedData['custom_name'];
            $room->custom_type = $validatedData['custom_type'];
            $room->custom_area = $validatedData['custom_area'];
            $room->custom_rate = $validatedData['custom_rate'];
            $room->expected_custom_rate = $expected_custom_rate;
        }
    
        $room->save();
    
       
    
        // Redirect based on room type
        switch ($validatedData['room_type']) {
            case 'Flat':
                return redirect()->route('admin.flats.index', ['building_id' => $validatedData['building_id']])
                    ->with('success', 'Room added successfully!');
            case 'Shops':
                return redirect()->route('admin.shops.index', ['building_id' => $validatedData['building_id']])
                    ->with('success', 'Room added successfully!');
            case 'Table space':
                return redirect()->route('admin.table-spaces.index', ['building_id' => $validatedData['building_id']])
                    ->with('success', 'Room added successfully!');
            case 'Kiosk':
                return redirect()->route('admin.kiosks.index', ['building_id' => $validatedData['building_id']])
                    ->with('success', 'Room added successfully!');
            case 'Chair space':
                return redirect()->route('admin.chair-spaces.index', ['building_id' => $validatedData['building_id']])
                    ->with('success', 'Room added successfully!');
            default:
                return redirect()->route('admin.rooms.index', ['building_id' => $validatedData['building_id']])
                    ->with('success', 'Room added successfully!');
        }
    }
    
    public function destroy($building_id, $room_id)
    {
        $building = Building::findOrFail($building_id);
        $room = $building->rooms()->findOrFail($room_id);
        $room->delete();

        return redirect()->back()->with('success', 'Room deleted successfully');
    }

   
    
    public function update(Request $request, $id)
    {
        // Fetch the room and associated building
        $room = Room::findOrFail($id);
        $building = Building::findOrFail($room->building_id);
    
        // Get the maximum number of floors from the building
        $maxFloors = $building->no_of_floors;
    
        // Validation rules including custom rule for room_floor
        $validatedData = $request->validate([
            'room_number' => 'required|string',
            'room_floor' => [
                'nullable',
                'string',
                'numeric',
                'max:' . $maxFloors,
            ],
            'room_type' => 'required|string',
            'flat_build_up_area' => 'nullable|numeric',
            'flat_carpet_area' => 'nullable|numeric',
            'flat_super_build_up_price' => 'nullable|numeric',
            'flat_carpet_area_price' => 'nullable|numeric',
            'build_up_area' => 'nullable|numeric',
            'carpet_area' => 'nullable|numeric',
            'flat_rate' => 'nullable|numeric',
            'super_build_up_price' => 'nullable|numeric',
            'carpet_area_price' => 'nullable|numeric',
            'shop_number' => 'nullable|string',
            'shop_type' => 'nullable|string',
            'shop_area' => 'nullable|numeric',
            'shop_rate' => 'nullable|numeric',
            'shop_rental_period' => 'nullable|string',
            'parking_number' => 'nullable|string',
            'parking_type' => 'nullable|string',
            'parking_area' => 'nullable|numeric',
            'parking_rate' => 'nullable|numeric',
            'space_name' => 'nullable|string',
            'space_type' => 'nullable|string',
            'space_area' => 'nullable|numeric',
            'space_rate' => 'nullable|numeric',
            'space_expected_price' => 'nullable|numeric',
            'kiosk_name' => 'nullable|string',
            'kiosk_type' => 'nullable|string',
            'kiosk_area' => 'nullable|numeric',
            'kiosk_rate' => 'nullable|numeric',
            'kiosk_expected_price' => 'nullable|numeric',
            'chair_name' => 'nullable|string',
            'chair_type' => 'nullable|string',
            'chair_material' => 'nullable|string',
            'chair_price' => 'nullable|numeric',
            'building_id' => 'required|exists:buildings,id',
            'flat_model' => 'nullable|string',
            'sale_amount' => 'nullable|string',
            'chair_space_in_sq' => 'nullable|string',
            'chair_space_rate' => 'nullable|string',
            'chair_space_expected_rate' => 'nullable|string',
        ], [
            'room_floor.max' => 'The room floor cannot be higher than the building\'s maximum floor of ' . $maxFloors,
        ]);
    
        // Calculate expected prices
        $expected_carpet_area_price = $validatedData['carpet_area'] && $validatedData['carpet_area_price']
            ? $validatedData['carpet_area_price'] * $validatedData['carpet_area']
            : null;
    
        $expected_super_buildup_area_price = $validatedData['build_up_area'] && $validatedData['super_build_up_price']
            ? $validatedData['build_up_area'] * $validatedData['super_build_up_price']
            : null;
    
        $flat_expected_carpet_area_price = $validatedData['flat_carpet_area'] && $validatedData['flat_carpet_area_price']
            ? $validatedData['flat_carpet_area_price'] * $validatedData['flat_carpet_area']
            : null;
    
        $flat_expected_super_buildup_area_price = $validatedData['flat_build_up_area'] && $validatedData['flat_super_build_up_price']
            ? $validatedData['flat_build_up_area'] * $validatedData['flat_super_build_up_price']
            : null;
    
        $space_expected_price = $validatedData['space_area'] && $validatedData['space_rate']
            ? $validatedData['space_area'] * $validatedData['space_rate']
            : null;
    
        $kiosk_expected_price = $validatedData['kiosk_area'] && $validatedData['kiosk_rate']
            ? $validatedData['kiosk_area'] * $validatedData['kiosk_rate']
            : null;
    
        $chair_space_expected_rate = $validatedData['chair_space_in_sq'] && $validatedData['chair_space_rate']
            ? $validatedData['chair_space_in_sq'] * $validatedData['chair_space_rate']
            : null;
    
        // Update the room with validated data
        $room->fill($validatedData);
    
        $room->expected_carpet_area_price = $expected_carpet_area_price;
        $room->expected_super_buildup_area_price = $expected_super_buildup_area_price;
        $room->flat_expected_carpet_area_price = $flat_expected_carpet_area_price;
        $room->flat_expected_super_buildup_area_price = $flat_expected_super_buildup_area_price;
        $room->space_expected_price = $space_expected_price;
        $room->kiosk_expected_price = $kiosk_expected_price;
        $room->chair_space_expected_rate = $chair_space_expected_rate;
    
        $room->save();
    
        return redirect()->back()->with('success', 'Room updated successfully!');
    }
    
    public function sell($id)
    {
        // Find the room by ID or fail with a 404 error if not found
        $room = Room::findOrFail($id);
        
        // Mark the room as sold
        $room->status = 'sold';
        $room->save();
        
        // Find the building that the room belongs to
        $buildingId = $room->building_id;

        // Redirect to the flats.index route for the building where the room is located
        return redirect()->route('flats.index', ['building_id' => $buildingId])
                        ->with('success', 'Room marked as sold.');
    }

       
    public function showSellForm($id, $buildingId)
    {
        $room = Room::findOrFail($id);
        $building = Building::findOrFail($buildingId);
        $partners = Partner::all();
        $availableFloors = Parking::where('status', 'available')
        ->distinct()
        ->pluck('floor_number');

        // Fetch all available parking slots
         $availableParkings = Parking::where('status', 'available')
        ->get(['id', 'slot_number', 'amount', 'floor_number']);

        return view('rooms.sell', [
            'room' => $room,
            'page' => 'rooms',
            'title' => 'Sell Room',
            'building' => $building,
            'partners' => $partners, // Remove quotes around $partners
            'availableFloors' => $availableFloors,
            'availableParkings' => $availableParkings,
        ]);
    }

        

    public function processSell(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        $request->validate([
            'sale_price' => 'required|numeric|min:0',
        ]);

        $room->status = 'sold';
        $room->sale_price = $request->input('sale_price');
        $room->save();

        return redirect()->route('admin.rooms.index')->with('success', 'Room marked as sold.');
    }
  
    public function showBuildingRooms($building_id)
    {
        $building = Building::with('rooms')->findOrFail($building_id);
        $rooms = $building->rooms;
        return view('rooms.show', compact('building', 'rooms'));
    }

    public function showRooms($building_id)
    {
        // Fetch rooms based on the building_id
        $rooms = Room::where('building_id', $building_id)->get();
    
        // Define room type labels and data (this is just an example)
        $roomTypeLabels = ['Flat', 'Shops', 'Table Space', 'Kiosk', 'Chair Space'];
        $roomTypeData = [
            // Example data, replace with actual data from your database
            'Flat' => $rooms->where('room_type', 'flat')->count(),
            'Shops' => $rooms->where('room_type', 'shop')->count(),
            'Table Space' => $rooms->where('room_type', 'table_space')->count(),
            'Kiosk' => $rooms->where('room_type', 'kiosk')->count(),
            'Chair Space' => $rooms->where('room_type', 'chair_space')->count(),
        ];
    
        // Pass the data to the view
        return view('rooms.show', [
            'rooms' => $rooms,
            'building_id' => $building_id,
            'roomTypeLabels' => $roomTypeLabels,
            'roomTypeData' => $roomTypeData,
        ]);
    }
    
    public function showFlats($building_id)
    {
        // Fetch the building
        $building = Building::findOrFail($building_id);
    
        // Fetch rooms (flats) for the specific building and eager load the sale relationship
        $rooms = Room::where('building_id', $building_id)
                     ->where('room_type', 'flat')
                     ->with('sale')  // Eager load the sale relationship
                     ->get();
    
                     $installments = Installment::all();
        // Passing the necessary data to the view
        $page = 'flats';
    
        return view('rooms.flats', compact('rooms', 'page', 'building_id', 'building','installments'));
    }
        
    public function showShops($buildingId)
    {
        // Fetch the building
        $building = Building::find($buildingId);
        
        // Fetch shops (rooms with room_type 'Shops')
        $shops = Room::where('building_id', $buildingId)
                     ->where('room_type', 'Shops') // Filter by room_type
                     ->get();
    
        $rooms = Room::where('building_id', $buildingId)
        ->where('room_type', 'Shops') // Filter by room_type
        ->get();
        $installments = Installment::all(); // Adjust according to your logic for fetching installments
        $page = 'Shops';
    
        // Pass the building_id to the view
        return view('rooms.shops', [
            'building_id' => $buildingId,
            'shops' => $shops,
            'rooms' => $rooms,
            'installments' => $installments,
            'page' => $page,
            'building' => $building,
        ]);
    }
    
    
    public function showTableSpaces($building_id)
{
    $building = Building::find($building_id);
    $rooms = Room::where('building_id', $building_id)
                 ->where('room_type', 'table space')
                 ->with('sales.installments') // Eager load sales and installments
                 ->get();
    
    // Assuming you want to get all installments related to the building's rooms
    $installments = Installment::all();
    $page = 'table-spaces'; 
    
    return view('rooms.table-spaces', compact('rooms', 'building', 'page', 'building_id', 'installments'));
}

    
    public function showKiosks($building_id)
    {
        $building = Building::find($building_id);
        $rooms = Room::where('building_id', $building_id)
                     ->where('room_type', 'Kiosk')
                     ->with('sales.installments')

                     ->get();
        $page = 'Kiosks'; 
        $installments = Installment::all();

        return view('rooms.kiosk', compact('building', 'rooms', 'page','building_id','installments'));
    }


    public function showChairSpaces($building_id)
{
    $building = Building::find($building_id);
    $rooms = Room::where('building_id', $building_id)
                 ->where('room_type', 'Chair space')
                 ->with('sales.installments')

                 ->get();
    $page = 'Chair space'; 
    $installments = Installment::all();

    return view('rooms.chair-space', compact('building', 'rooms', 'page','building_id','installments'));
}

   public function difference($id)
   {
       $building = Building::find($id);
       $floorRooms = Room::where('building_id', $id)
           ->where('room_type', 'flat')
           ->orderBy('room_floor')
           ->get()
           ->groupBy('room_floor');
   
       $title = "Flat Differences";
       $page = "Flat Differences"; // or any other value you need
   
       return view('flats.difference', compact('building', 'floorRooms', 'title', 'page'));
   }
   public function shopsDifference($building_id)
{
    // Fetch building information
    $building = Building::findOrFail($building_id);

    // Fetch rooms for the specified building and room type 'Shops'
    $rooms = Room::where('building_id', $building_id)
                  ->where('room_type', 'Shops')
                  ->get()
                  ->groupBy('room_floor');

    // Fetch sales for the rooms
    $sales = Sale::whereIn('room_id', $rooms->pluck('id'))->get()->groupBy('room_id');

    // Pass the data to the view
    return view('shops.difference', [
        'title' => 'Shops Difference',
        'page' => 'shops_difference',
        'building_id' => $building_id,
        'building' => $building,
        'rooms' => $rooms,
        'sales' => $sales
    ]);
}

    public function totalCustomers()
    {
        $customers = Sale::select(
            'sales.customer_name',
            'sales.sale_amount',
            'sales.in_hand_amount',
            'sales.gst_amount',
            'sales.total_amount',
            'rooms.room_type',
            'sales.remaining_balance'
        )
        ->join('rooms', 'sales.room_id', '=', 'rooms.id')
        ->get();

        $data = [
            'customers' => $customers,
        ];

        return view('admin.customers.total_customers', $data);
    }
    public function showCustomerDetails($customerId)
    {
        $sales = Sale::where('customer_name', $customerId)
            ->with(['room.building', 'installments']) // Ensure to load the installments relationship
            ->get();
    
        return view('customers.show', compact('sales'));
    }
    public function kioskDifference($buildingId)
    {
        $building = Building::findOrFail($buildingId);
        $kiosks = Room::where('building_id', $buildingId)
        ->where('room_type', 'Kiosk')
        ->get()
        ->groupBy('room_floor');
        
        return view('kiosk.difference', [
            'title' => 'Kiosk Difference',
            'page' => 'kiosk_difference',
            'building' => $building,
            'kiosks' => $kiosks,        ]);
    }

    public function showChairSpaceDifference($building_id)
    {
        // Fetch the building data
        $building = Building::findOrFail($building_id);
    
        // Fetch chair spaces data based on building_id
        $chairSpaces = Room::where('building_id', $building_id)
                           ->where('room_type', 'Chair Space')
                           ->get();
    
        // Pass the data to the view
        return view('chair_spaces.difference', [
            'building' => $building,
            'chairSpacesData' => $chairSpaces
        ]);
    }
    public function showTableSpaceDifference($building_id)
    {
        // Fetch the building data
        $building = Building::findOrFail($building_id);
    
        // Fetch table spaces data based on building_id
        $tableSpaces = Room::where('building_id', $building_id)
                           ->where('room_type', 'Table Space')
                           ->get();
    
        // Pass the data to the view
        return view('table_spaces.difference', [
            'building' => $building,
            'tableSpacesData' => $tableSpaces
        ]);
    }
    
        
    public function showCustomRooms($building_id)
    {
        $building = Building::find($building_id);
    
        $predefinedRoomTypes = ['Flat', 'Shops', 'Table Space', 'Chair Space', 'Kiosk'];
    
        // Use Eloquent model to fetch rooms
        $rooms = Room::where('building_id', $building_id)
            ->whereNotIn('room_type', $predefinedRoomTypes)
            ->whereNull('deleted_at')
            ->with('sales') // Ensure 'sales' relationship is eager-loaded
            ->get();
    
        $page = 'custom-rooms'; // Or any other appropriate value
    
        return view('rooms.custom_rooms', compact('rooms', 'building_id', 'page', 'building'));
    }

        
    public function otherRoomTypesDifference($buildingId)
    {
        // Predefined room types to exclude
        $predefinedRoomTypes = ['Flat', 'Shops', 'Table Space', 'Chair Space', 'Kiosk'];

        // Find the building
        $building = Building::findOrFail($buildingId);

        // Fetch rooms with room types that are NOT in the predefined list and group them by floor
        $rooms = Room::where('building_id', $buildingId)
            ->whereNotIn('room_type', $predefinedRoomTypes)
            ->whereNull('deleted_at')
            ->with('sales') // Eager-load the sales relationship
            ->get()
            ->groupBy('room_floor'); // Group rooms by floor
        
        // Pass data to the view
        return view('rooms.other_types_difference', [
            'title' => 'Other Room Types Difference',
            'page' => 'other_room_types_difference',
            'building' => $building,
            'rooms' => $rooms,
        ]);
    }

    
}