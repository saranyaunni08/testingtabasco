<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\Building;
use App\Models\Sale;
use App\Models\MasterSetting; 
use Illuminate\Support\Facades\Log;



class RoomController extends Controller
{
    public function index(Request $request)
    {
        $building = Building::findOrFail($request->building_id);
        $rooms = Room::where('building_id', $building->id)->paginate(10);
        $page = 'rooms';
        $building_id = $request->building_id;

        return view('rooms.show', compact('rooms', 'building', 'page', 'building_id'));
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

    public function edit($id)
    {
        $room = Room::findOrFail($id);
        $page = 'edit';
        return view('rooms.edit', compact('room', 'page'));
    }

    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);
        $room->update($request->all());
        return redirect()->back()->with('success', 'Room updated successfully');
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
    public function show($buildingId) {
        $rooms = Room::where('building_id', $buildingId)->paginate(10);
        $master_settings = MasterSetting::first(); 
        return view('rooms.show', compact('rooms', 'master_settings'));
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
    
        // Passing the necessary data to the view
        $page = 'flats';
    
        return view('rooms.flats', compact('rooms', 'page', 'building_id', 'building'));
    }
        public function showShops($building_id)
    {
        $building = Building::find($building_id);
        $rooms = Room::where('building_id', $building_id)->where('room_type', 'Shops')->get();
        $page = 'Shops'; 

        Log::info('Fetched rooms:', $rooms->toArray());
    
        return view('rooms.shops', compact('rooms', 'building', 'page','building_id'));
    }
    public function showTableSpaces($building_id)
    {
        $building = Building::find($building_id);
        $rooms = Room::where('building_id', $building_id)->where('room_type', 'table space')->get();
        $page = 'table-spaces'; 
    
        return view('rooms.table-spaces', compact('rooms', 'building', 'page','building_id'));
    }
    public function kiosks($building_id)
    {
        $building = Building::find($building_id);
        $rooms = Room::where('building_id', $building_id)
                     ->where('room_type', 'Kiosk')
                     ->get();
        $page = 'Kiosks'; 
        return view('rooms.kiosk', compact('building', 'rooms', 'page','building_id'));
    }
    public function chairSpaces($building_id)
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
