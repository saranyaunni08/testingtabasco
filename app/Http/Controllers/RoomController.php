<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;


class RoomController extends Controller
{

    public function create()
    {
        // This method should return the view for creating a new room
        return view('rooms.create');
    }

    public function edit($id)
    {
        // Retrieve the room by ID for editing
        $room = Room::findOrFail($id);
    
        // Define the $page variable
        $page = 'edit';
    
        // Pass the room data and $page variable to the edit view
        return view('rooms.edit', compact('room', 'page'));
    }
    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);
    
        // Update room properties based on the form data
        $room->update([
            'room_number' => $request->input('room_number'),
            'room_floor' => $request->input('room_floor'),
            'room_type' => $request->input('room_type'),
            'build_up_area' => $request->input('build_up_area'),
            'carpet_area' => $request->input('carpet_area'),
            'flat_rate' => $request->input('flat_rate'),
            'super_build_up_area' => $request->input('super_build_up_area'),
            'carpet_area_price' => $request->input('carpet_area_price'),
        ]);
    
        return redirect()->route('admin.rooms.index')->with('success', 'Room updated successfully');
    }
    public function index()
    {
        // Fetch all rooms
        $rooms = Room::all();

        // Filter rooms by type
        $flats = $rooms->where('room_type', 'Flat');
        $shops = $rooms->where('room_type', 'Shops');
        $carParking = $rooms->where('room_type', 'Car parking');
        $tableSpaces = $rooms->where('room_type', 'Table space');
        $chairSpaces = $rooms->where('room_type', 'Chair space');
        $kiosks = $rooms->where('room_type', 'Kiosk');

        // Pass data to the view
        return view('rooms.index', [
            'flats' => $flats,
            'shops' => $shops,
            'carParking' => $carParking,
            'tableSpaces' => $tableSpaces,
            'chairSpaces' => $chairSpaces,
            'kiosks' => $kiosks,
        ]);

        $rooms = Room::withTrashed()->get();

        return view('rooms.index', compact('rooms'));
    }

    protected function getRoomsWithTrashed(bool $withTrashed = true): Builder|Room
    {
        return Room::withTrashed($withTrashed);
    }
    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->delete(); // Soft delete the room
    
        return redirect()->route('admin.rooms.create')->with('success', 'Room deleted successfully');
    }
    public function store(Request $request)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'room_number' => 'required|string',
            'room_floor' => 'nullable|string',
            'room_type' => 'required|string',
            'build_up_area' => 'nullable|numeric', // Assuming these are numeric fields
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
        ]);

        // Create a new Room instance and fill it with validated data
        $room = new Room();
        $room->room_number = $validatedData['room_number'];
        $room->room_floor = $validatedData['room_floor'];
        $room->room_type = $validatedData['room_type'];

        // Fill specific fields based on room type
        switch ($validatedData['room_type']) {
            case 'Flat':
                $room->build_up_area = $validatedData['build_up_area'];
                $room->carpet_area = $validatedData['carpet_area'];
                $room->flat_rate = $validatedData['flat_rate'];
                $room->super_build_up_price = $validatedData['super_build_up_price'];
                $room->carpet_area_price = $validatedData['carpet_area_price'];
                break;
            case 'Shops':
                $room->shop_number = $validatedData['shop_number'];
                $room->shop_type = $validatedData['shop_type'];
                $room->shop_area = $validatedData['shop_area'];
                $room->shop_rate = $validatedData['shop_rate'];
                $room->shop_rental_period = $validatedData['shop_rental_period'];
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

        // Save the room
        $room->save();

        // Redirect back or to a success page
        return redirect()->back()->with('success', 'Room added successfully!');
    }
}
