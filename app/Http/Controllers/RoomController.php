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
        // Validate and get the building ID from the request
        $building_id = $request->input('building_id');
        
        // Find the building by ID
        $building = Building::findOrFail($building_id);
    
        // Retrieve rooms associated with the building and paginate them
        $rooms = Room::where('building_id', $building->id)->paginate(10);
    
        // Calculate room statistics
        $roomStats = [
            'Flat Expected Amount' => [
                'count' => $rooms->where('room_type', 'Flat')->count(),
                'total' => $rooms->where('room_type', 'Flat')->sum('flat_expected_carpet_area_price'),
            ],
            'Shops Expected Amount' => [
                'count' => $rooms->where('room_type', 'Shops')->count(),
                'total' => $rooms->where('room_type', 'Shops')->sum('expected_carpet_area_price'),
            ],
            'Table space Expected Amount' => [
                'count' => $rooms->where('room_type', 'Table space')->count(),
                'total' => $rooms->where('room_type', 'Table space')->sum('space_expected_price'),
            ],
            'Kiosk Expected Amount' => [
                'count' => $rooms->where('room_type', 'Kiosk')->count(),
                'total' => $rooms->where('room_type', 'Kiosk')->sum('kiosk_expected_price'),
            ],
            'Chair space Expected Amount' => [
                'count' => $rooms->where('room_type', 'Chair space')->count(),
                'total' => $rooms->where('room_type', 'Chair space')->sum('chair_space_expected_rate'),
            ],
        ];
    
        // Calculate the total expected amount
        $totalExpectedAmount = array_sum(array_column($roomStats, 'total'));
    
        // Calculate the total expected amount for sold room types
        $soldRooms = $rooms->where('status', 'sold');
        $soldAmountData = [
            'Flat Expected Amount' => $soldRooms->where('room_type', 'Flat')->sum('flat_expected_carpet_area_price'),
            'Shops Expected Amount' => $soldRooms->where('room_type', 'Shops')->sum('expected_carpet_area_price'),
            'Table space Expected Amount' => $soldRooms->where('room_type', 'Table space')->sum('space_expected_price'),
            'Kiosk Expected Amount' => $soldRooms->where('room_type', 'Kiosk')->sum('kiosk_expected_price'),
            'Chair space Expected Amount' => $soldRooms->where('room_type', 'Chair space')->sum('chair_space_expected_rate'),
        ];
    
        // Prepare data for charts
        $totalFlats = $roomStats['Flat Expected Amount']['count'];
        $totalShops = $roomStats['Shops Expected Amount']['count'];
        $totalTableSpaces = $roomStats['Table space Expected Amount']['count'];
        $totalKiosks = $roomStats['Kiosk Expected Amount']['count'];
        $totalChairSpaces = $roomStats['Chair space Expected Amount']['count'];
    
        // Example data for the bar chart (replace with actual data)
        $buildings = Building::all(); // Retrieve buildings data
        $expectedPriceData = [
            'Flat Expected Amount' => $roomStats['Flat Expected Amount']['total'],
            'Shops Expected Amount' => $roomStats['Shops Expected Amount']['total'],
            'Table space Expected Amount' => $roomStats['Table space Expected Amount']['total'],
            'Kiosk Expected Amount' => $roomStats['Kiosk Expected Amount']['total'],
            'Chair space Expected Amount' => $roomStats['Chair space Expected Amount']['total'],
        ];
    
        $page = 'rooms'; // Define $page variable
    
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
            'totalExpectedAmount' // Pass total expected amount to the view
        ));
    }
    
    public function create(Request $request)
    {
        $building_id = $request->building_id; 
        $room_type = $request->room_type;
        $building = Building::findOrFail($building_id);
    
        return view('rooms.create', compact('building_id', 'building', 'room_type'));
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
        $room = Room::findOrFail($id);
        $room->status = 'sold';
        $room->save();
        return redirect()->route('admin.rooms.index')->with('success', 'Room marked as sold.');
    }

    public function showSellForm($id)
    {
        $room = Room::findOrFail($id);
        return view('rooms.sell', [
            'room' => $room,
            'page' => 'rooms' 
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
        $type = 'Chair Space'; 
    
        $chairSpaces = Room::where('building_id', $building_id)
                           ->where('room_type', 'Chair Space')
                           ->get();
    
        $page = 'chair-spaces'; 
    
        return view('rooms.chair-space', compact('chairSpaces', 'type', 'page','building_id'));
    }
   // In RoomController.php
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

        // Pass the data to the view
        return view('shops.difference', [
            'title' => 'Shops Difference',
            'page' => 'shops_difference',
            'building_id' => $building_id,
            'building' => $building,
            'rooms' => $rooms
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
    
}    
