<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    // Show the form to create a new room type
    public function create() {
        $roomTypes = RoomType::all();

        return view('room_types.create', [
            'page' => 'create-roomtype',
            'title' => 'create-roomtype', // Passing the title
            'roomTypes' => 'roomTypes',

        ]);
    }
    // Store the new room type
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        RoomType::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.room_types.index')->with('success', 'Room type added successfully!');
    }

    // Display the list of room types
    public function index()
    {
        $roomTypes = RoomType::all();
        return view('room_types.index', [
            'title' => 'Room Types List', // Passing the title
            'roomTypes' => $roomTypes,
            'page' => 'room-types-list' // or the appropriate page value

        ]);
    }
    
}
