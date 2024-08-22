<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\Building;
use App\Models\Sale;
use App\Models\Installment;
use Illuminate\Support\Facades\Log;



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
        $flatRooms = $rooms->filter(function($room) {
            return $room->room_type === 'Flat';
        });

        $ShopRooms = $rooms->filter(function($room) {
             return $room->room_type === 'Shops';
        });
        $TableRooms = $rooms->filter(function($room) {
             return $room->room_type === 'Table space';
        });
    
        $KioskRooms = $rooms->filter(function($room) {
             return $room->room_type === 'Kiosk';
        });
        $ChairRooms = $rooms->filter(function($room) {
             return $room->room_type === 'Chair space';
        });
    
        // Calculate sold amount and expected amount for flats
        $soldAmount = Sale::whereIn('room_id', $flatRooms->pluck('id'))->sum('total_amount');
        $expectedAmount = $flatRooms->sum('flat_expected_super_buildup_area_price');
        $totalBuildUpArea = $flatRooms->sum('flat_build_up_area');
        $soldBuildUpArea = $flatRooms->where('status', 'sold')->sum('flat_build_up_area');
        $allFlatsSold = $flatRooms->where('status', 'available')->isEmpty();
        $profitOrLoss = $soldAmount - $expectedAmount;
        $profitOrLossText = ($profitOrLoss > 0) ? 'profit' : 'loss';
        $profitOrLossColor = ($profitOrLoss > 0) ? 'green' : 'red';

        //shops
        $soldShopAmount = Sale::whereIn('room_id', $ShopRooms->pluck('id'))->sum('total_amount');
        $expectedAmountShop = $ShopRooms->sum('expected_super_buildup_area_price');

        $totalShopBuildUpArea = $rooms->sum('build_up_area');
        $soldShopBuildUpArea = $ShopRooms->where('status', 'sold')->sum('build_up_area');
        $allShopsSold = $ShopRooms->where('status', 'available')->isEmpty();

        $profitOrLossShop = $soldShopAmount - $expectedAmountShop;
        $profitOrLossTextShop = ($profitOrLossShop > 0) ? 'profit' : 'loss';
        $profitOrLossColorShop = ($profitOrLossShop > 0) ? 'green' : 'red';

       

       //Table space Expected Amount
       $soldTableAmount = Sale::whereIn('room_id', $TableRooms->pluck('id'))->sum('total_amount');
       $expectedAmountTable = $TableRooms->sum('space_expected_price');

        $totalTableBuildUpArea = $rooms->sum('space_area');
        $soldTableBuildUpArea = $TableRooms->where('status', 'sold')->sum('space_area');
        $allTableSold = $TableRooms->where('status', 'available')->isEmpty();

        $profitOrLossTable = $soldTableAmount - $expectedAmountTable;
        $profitOrLossTextTable = ($profitOrLossTable > 0) ? 'profit' : 'loss';
        $profitOrLossColorTable = ($profitOrLossTable > 0) ? 'green' : 'red';

        //Kiosk Expected Amount
        $soldKioskAmount = Sale::whereIn('room_id', $KioskRooms->pluck('id'))->sum('total_amount');
        $expectedAmountKiosk = $KioskRooms->sum('kiosk_expected_price');


        $totalKioskBuildUpArea = $rooms->sum('kiosk_area');
        $soldKioskBuildUpArea = $KioskRooms->where('status', 'sold')->sum('kiosk_area');
        $allKioskSold = $KioskRooms->where('status', 'available')->isEmpty();

        $profitOrLossKiosk = $soldKioskAmount - $expectedAmountKiosk;
        $profitOrLossTextKiosk = ($profitOrLossKiosk > 0) ? 'profit' : 'loss';
        $profitOrLossColorKiosk = ($profitOrLossKiosk > 0) ? 'green' : 'red';
    
        //Chair space Expected Amount
        $soldChairAmount = Sale::whereIn('room_id', $ChairRooms->pluck('id'))->sum('total_amount');
        $expectedAmountKiosk = $ChairRooms->sum('chair_space_expected_rate');


        $totalChairBuildUpArea = $rooms->sum('chair_space_in_sq');
        $soldChairBuildUpArea = $ChairRooms->where('status', 'sold')->sum('chair_space_in_sq');
        $allChairSold = $ChairRooms->where('status', 'available')->isEmpty();
        
        $profitOrLossChair = $soldChairAmount - $expectedAmountKiosk;
        $profitOrLossTextChair = ($profitOrLossChair > 0) ? 'profit' : 'loss';
        $profitOrLossColorChair = ($profitOrLossChair > 0) ? 'green' : 'red';

     
       

        // Calculate room statistics for different types
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
                'total' => $ShopRooms->sum('expected_carpet_area_price'),
                'totalShopBuildUpArea' => $totalShopBuildUpArea,
                'soldShopBuildUpArea' => $soldShopBuildUpArea,
                'soldShopAmount' => $soldShopAmount,
                'allShopsSold' => $allShopsSold,
                'profitOrLossShop'=> $profitOrLossShop,
                'profitOrLossTextShop'=> $profitOrLossTextShop,
                'profitOrLossColorShop'=> $profitOrLossColorShop,

            ],
            'Table space Expected Amount' => [
                'count' => $TableRooms->count(),
                'total' => $TableRooms->sum('space_expected_price'),
                'totalTableBuildUpArea' => $totalTableBuildUpArea,
                'soldTableBuildUpArea' => $soldTableBuildUpArea,
                'soldTableAmount' => $soldTableAmount,
                'allTableSold' => $allTableSold,
                'profitOrLossTable'=> $profitOrLossTable,
                'profitOrLossTextTable'=> $profitOrLossTextTable,
                'profitOrLossColorTable'=> $profitOrLossColorTable,

               
            ],
            'Kiosk Expected Amount' => [
                'count' => $KioskRooms->count(),
                'total' => $KioskRooms->sum('kiosk_expected_price'),
                'totalKioskBuildUpArea' => $totalKioskBuildUpArea,
                'soldKioskBuildUpArea' => $soldKioskBuildUpArea,
                'soldKioskAmount' => $soldKioskAmount,
                'allKioskSold' => $allKioskSold,
                'profitOrLossKiosk'=> $profitOrLossKiosk,
                'profitOrLossTextKiosk'=> $profitOrLossTextKiosk,
                'profitOrLossColorKiosk'=> $profitOrLossColorKiosk,
            ],
            'Chair space Expected Amount' => [
                'count' => $ChairRooms->count(),
                'total' => $ChairRooms->sum('chair_space_expected_rate'),
                'totalChairBuildUpArea' => $totalChairBuildUpArea,
                'soldChairBuildUpArea' => $soldChairBuildUpArea,
                'soldChairAmount' => $soldChairAmount,
                'allChairSold' => $allChairSold,

                'profitOrLossChair'=> $profitOrLossChair,
                'profitOrLossTextChair'=> $profitOrLossTextChair,
                'profitOrLossColorChair'=> $profitOrLossColorChair,

              
            ],
        ];
    
        $totalExpectedAmount = array_sum(array_column($roomStats, 'total'));    
        // Calculate sold amounts for different room types
        $soldAmountData = [
            'Flat Expected Amount' => $flatRooms->where('status', 'sold')->sum('flat_expected_carpet_area_price'),
            'Shops Expected Amount' => $ShopRooms->where('status', 'sold')->sum('expected_carpet_area_price'),
            'Table space Expected Amount' => $TableRooms->where('status', 'sold')->sum('space_expected_price'),
            'Kiosk Expected Amount' => $KioskRooms->where('status', 'sold')->sum('kiosk_expected_price'),
            'Chair space Expected Amount' => $ChairRooms->where('status', 'sold')->sum('chair_space_expected_rate'),
        ];
    
        // Room type counts
        $totalFlats = $roomStats['Flat Expected Amount']['count'];
        $totalShops = $roomStats['Shops Expected Amount']['count'];
        $totalTableSpaces = $roomStats['Table space Expected Amount']['count'];
        $totalKiosks = $roomStats['Kiosk Expected Amount']['count'];
        $totalChairSpaces = $roomStats['Chair space Expected Amount']['count'];
    
        // Building data
        $buildings = Building::all();
    
        // Expected price data
        $expectedPriceData = [
            'Flat Expected Amount' => $roomStats['Flat Expected Amount']['total'],
            'Shops Expected Amount' => $roomStats['Shops Expected Amount']['total'],
            'Table space Expected Amount' => $roomStats['Table space Expected Amount']['total'],
            'Kiosk Expected Amount' => $roomStats['Kiosk Expected Amount']['total'],
            'Chair space Expected Amount' => $roomStats['Chair space Expected Amount']['total'],
        ];
    
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

        ));
    }
    
    public function store(Request $request)
    {
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
            'sale_amount' => 'nullable|string',
            'chair_space_in_sq' => 'nullable|string',
            'chair_space_rate' => 'nullable|string',
            'chair_space_expected_rate' => 'nullable|string',
            
        ]);

        if ($validatedData['carpet_area'] && $validatedData['carpet_area_price']) {
            $expected_carpet_area_price = $validatedData['carpet_area_price'] * $validatedData['carpet_area'];
        } else {
            $expected_carpet_area_price = null;
        }

        if ($validatedData['build_up_area'] && $validatedData['super_build_up_price']) {
            $expected_super_buildup_area_price = $validatedData['build_up_area'] * $validatedData['super_build_up_price'];
        } else {
            $expected_super_buildup_area_price = null;
        }

        if ($validatedData['flat_carpet_area'] && $validatedData['flat_carpet_area_price']) {
            $flat_expected_carpet_area_price = $validatedData['flat_carpet_area'] * $validatedData['flat_carpet_area_price'];
        }else {
            $flat_expected_carpet_area_price = null;
        }

        if ($validatedData['flat_build_up_area'] && $validatedData['flat_super_build_up_price']) {
            $flat_expected_super_buildup_area_price = $validatedData['flat_build_up_area'] * $validatedData['flat_super_build_up_price'];
        }else {
            $flat_expected_super_buildup_area_price = null;
        }

        if ($validatedData['space_area'] && $validatedData['space_rate']) {
            $space_expected_price = $validatedData['space_area'] * $validatedData['space_rate'];
        } else {
            $space_expected_price =null;
        }

        if ($validatedData ['kiosk_area'] && $validatedData ['kiosk_rate']) {
            $kiosk_expected_price = $validatedData ['kiosk_area'] * $validatedData ['kiosk_rate'];
        } else {
            $kiosk_expected_price = null;
        }

        if ($validatedData ['chair_space_in_sq'] && $validatedData ['chair_space_rate']) {
            $chair_space_expected_rate = $validatedData ['chair_space_in_sq'] * $validatedData ['chair_space_rate'];
        } else {
            $chair_space_expected_rate = null;
        }

        $room = new Room();
        $room->fill($validatedData);

        $room->expected_carpet_area_price = $expected_carpet_area_price;
        $room->expected_super_buildup_area_price = $expected_super_buildup_area_price;
        $room-> flat_expected_carpet_area_price = $flat_expected_carpet_area_price;
        $room-> flat_expected_super_buildup_area_price = $flat_expected_super_buildup_area_price;
        $room-> space_expected_price = $space_expected_price;
        $room-> kiosk_expected_price = $kiosk_expected_price;
        $room-> chair_space_expected_rate = $chair_space_expected_rate;


        $room->save();

        return redirect()->back()->with('success', 'Room added successfully!');
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
            'sale_amount' => 'nullable|string',
            'chair_space_in_sq' => 'nullable|string',
            'chair_space_rate' => 'nullable|string',
            'chair_space_expected_rate' => 'nullable|string',
        ]);
    
        $room = Room::findOrFail($id);
    
        if ($validatedData['carpet_area'] && $validatedData['carpet_area_price']) {
            $expected_carpet_area_price = $validatedData['carpet_area_price'] * $validatedData['carpet_area'];
        } else {
            $expected_carpet_area_price = null;
        }
    
        if ($validatedData['build_up_area'] && $validatedData['super_build_up_price']) {
            $expected_super_buildup_area_price = $validatedData['build_up_area'] * $validatedData['super_build_up_price'];
        } else {
            $expected_super_buildup_area_price = null;
        }
    
        if ($validatedData['flat_carpet_area'] && $validatedData['flat_carpet_area_price']) {
            $flat_expected_carpet_area_price = $validatedData['flat_carpet_area'] * $validatedData['flat_carpet_area_price'];
        } else {
            $flat_expected_carpet_area_price = null;
        }
    
        if ($validatedData['flat_build_up_area'] && $validatedData['flat_super_build_up_price']) {
            $flat_expected_super_buildup_area_price = $validatedData['flat_build_up_area'] * $validatedData['flat_super_build_up_price'];
        } else {
            $flat_expected_super_buildup_area_price = null;
        }
    
        Log::info('Updating room', [
            'room_id' => $id,
            'flat_build_up_area' => $validatedData['flat_build_up_area'],
            'flat_super_build_up_price' => $validatedData['flat_super_build_up_price'],
            'flat_expected_super_buildup_area_price' => $flat_expected_super_buildup_area_price,
        ]);
    
        if ($validatedData['space_area'] && $validatedData['space_rate']) {
            $space_expected_price = $validatedData['space_area'] * $validatedData['space_rate'];
        } else {
            $space_expected_price = null;
        }
    
        if ($validatedData['kiosk_area'] && $validatedData['kiosk_rate']) {
            $kiosk_expected_price = $validatedData['kiosk_area'] * $validatedData['kiosk_rate'];
        } else {
            $kiosk_expected_price = null;
        }
    
        if ($validatedData['chair_space_in_sq'] && $validatedData['chair_space_rate']) {
            $chair_space_expected_rate = $validatedData['chair_space_in_sq'] * $validatedData['chair_space_rate'];
        } else {
            $chair_space_expected_rate = null;
        }
    
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

    public function showSellForm($id)
    {
        $room = Room::findOrFail($id);
        return view('rooms.sell', [
            'room' => $room,
            'page' => 'rooms',
            'title' => 'Sell Room'  // Add this line
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
        $rooms = Room::where('building_id', $building_id)->get();

        return view('rooms.index', [
            'rooms' => $rooms,
            'building_id' => $building_id, 
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
    
        $installments = Installment::whereIn('sale_id', $rooms->pluck('id'))->get();

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
        $rooms = Room::where('building_id', $building_id)->where('room_type', 'table space')->get();
        $page = 'table-spaces'; 
    
        return view('rooms.table-spaces', compact('rooms', 'building', 'page','building_id'));
    }
    
    public function showKiosks($building_id)
    {
        $building = Building::find($building_id);
        $rooms = Room::where('building_id', $building_id)
                     ->where('room_type', 'Kiosk')
                     ->get();
        $page = 'Kiosks'; 
        return view('rooms.kiosk', compact('building', 'rooms', 'page','building_id'));
    }
    public function showChairSpaces($building_id)
    {
        $building = Building::findOrFail($building_id);
    
        $chairSpaces = Room::where('building_id', $building_id)
            ->where('room_type', 'Chair Space')
            ->get();
    
        // Prepare data for the view
        $chairSpacesData = $chairSpaces->map(function($room) {
            $totalAmount = $room->sales->sum('total_amount');
            $saleAmount = $room->sales->sum('sale_amount');
            $expectedAmount = $room->chair_space_expected_rate * $room->chair_space_area;
            $difference = $totalAmount - $expectedAmount;
            $isPositive = $difference > 0;
            $showDifference = empty($room->status);
            return [
                'room' => $room,
                'expected_amount' => $expectedAmount,
                'total_amount' => $totalAmount,
                'sale_amount' => $saleAmount,
                'difference' => $difference,
                'is_positive' => $isPositive,
                'show_difference' => $showDifference,
                'status' => $room->status
            ];
        });
    
        return view('rooms.chair-space', [
            'building' => $building,
            'chairSpacesData' => $chairSpacesData,
            'type' => 'Chair Space',
            'page' => 'chair-spaces',
            'building_id' => $building_id,
        ]);
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
    
    // public function showSellPage($buildingId)
    // {
    //     Log::info('Building ID:', ['id' => $buildingId]);
        
    //     $building = Building::find($buildingId);
    
    //     if (!$building) {
    //         abort(404, 'Building not found');
    //     }
    
    //     $rooms = Room::where('building_id', $buildingId)->get();
    //     $title = "Sell Room - " . $buildingId;
    //     $page = "rooms";
    
    //     return view('rooms.sell', compact('building', 'rooms', 'title', 'page'));
    // }
    
}    