<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\Building;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::all(); // Fetching all rooms
        $page = 'rooms'; // Defining the page variable
    
        // Fetching the building_id dynamically, for example, from a Building model
        $building = Building::first(); // Adjust to fetch the desired building
        $building_id = $building ? $building->id : null;
    
        return view('rooms.show', compact('rooms', 'building_id', 'page'));
    }

    public function create($building_id)
    {
        return view('rooms.create', compact('building_id'));
    }
    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'room_number' => 'required|string',
            'room_floor' => 'nullable|string',
            'room_type' => 'required|string',
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
            'kiosk_name' => 'nullable|string',
            'kiosk_type' => 'nullable|string',
            'kiosk_area' => 'nullable|numeric',
            'kiosk_rate' => 'nullable|numeric',
            'chair_name' => 'nullable|string',
            'chair_type' => 'nullable|string',
            'chair_material' => 'nullable|string',
            'chair_price' => 'nullable|numeric',
            'building_id' => 'required|exists:buildings,id',
            'flat_model' => 'nullable|string',
            'sale_amount' => 'nullable|string',
        ]);

        $room = new Room();
        $room->fill($validatedData);

        switch ($validatedData['room_type']) {
            case 'Flat':
                $room->flat_model = $validatedData['flat_model'];
                $room->build_up_area = $validatedData['build_up_area'];
                $room->carpet_area = $validatedData['carpet_area'];
                $room->super_build_up_price = $validatedData['super_build_up_price'];
                $room->carpet_area_price = $validatedData['carpet_area_price'];
                break;
            case 'Shops':
                $room->shop_type = $validatedData['shop_type'];
                $room->shop_area = $validatedData['shop_area'];
                $room->shop_rate = $validatedData['shop_rate'];
                break;
            case 'Car parking':
                $room->parking_number = $validatedData['parking_number'];
                $room->parking_type = $validatedData['parking_type'];
                $room->parking_area = $validatedData['parking_area'];
                $room->parking_rate = $validatedData['parking_rate'];
                break;
            case 'Table space':
                $room->space_name = $validatedData['space_name'];
                $room->space_type = $validatedData['space_type'];
                $room->space_area = $validatedData['space_area'];
                $room->space_rate = $validatedData['space_rate'];
                break;
            case 'Kiosk':
                $room->kiosk_name = $validatedData['kiosk_name'];
                $room->kiosk_type = $validatedData['kiosk_type'];
                $room->kiosk_area = $validatedData['kiosk_area'];
                $room->kiosk_rate = $validatedData['kiosk_rate'];
                break;
            case 'Chair space':
                $room->chair_name = $validatedData['chair_name'];
                $room->chair_type = $validatedData['chair_type'];
                $room->chair_material = $validatedData['chair_material'];
                $room->chair_price = $validatedData['chair_price'];
                break;
        }

        $room->save();

        return redirect()->back()->with('success', 'Room added successfully!');
    }

    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->delete();
        return redirect()->route('admin.rooms.index')->with('success', 'Room deleted successfully');
    }

    public function show($id)
    {
        $building = Building::findOrFail($id);
        $building_id = $building->id;

        return view('pages.building', compact('building', 'building_id'));
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
        return Redirect::route('admin.buildings.show', ['id' => $room->building_id])->with('success', 'Room updated successfully');
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

}

