<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\room; 
use App\Models\Building; 

class BuildingController extends Controller
{
    public function index()
    {
        $buildings = DB::table('building')->get();
        return view('pages.building', compact('buildings'));
    }

    public function create()
    {
        return view('pages.addbuilding');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'building_name' => 'required|string',
            'no_of_floors' => 'required|integer|min:1',
            'building_address' => 'required|string',
            'street' => 'required|string',
            'city' => 'required|string',
            'pin_code' => 'required|integer',
            'state' => 'required|string',
            'country' => 'required|string',
            'super_built_up_area' => 'required|integer',
            'carpet_area' => 'required|integer',
        ]);

        $checkboxAmenities = implode(',', $request->input('building_amenities', []));

        DB::table('building')->insert([
            'building_name' => $validatedData['building_name'],
            'no_of_floors' => $validatedData['no_of_floors'],
            'building_address' => $validatedData['building_address'],
            'street' => $validatedData['street'],
            'city' => $validatedData['city'],
            'pin_code' => $validatedData['pin_code'],
            'state' => $validatedData['state'],
            'country' => $validatedData['country'],
            'super_built_up_area' => $validatedData['super_built_up_area'],
            'carpet_area' => $validatedData['carpet_area'],
            'building_amenities' => $checkboxAmenities,
            'additional_amenities' => $request->input('additional_amenities'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.building')->with('success', 'Building added successfully');
    }

    public function edit($id)
    {
        $building = DB::table('building')->where('id', $id)->first();

        $amenities = explode(',', $building->building_amenities);
        $checkboxAmenities = [];

        foreach ($amenities as $amenity) {
            if (in_array($amenity, ['Parking', 'Gym', 'Swimming Pool'])) {
                $checkboxAmenities[] = $amenity;
            }
        }

        $additionalAmenities = $building->additional_amenities;

        return view('pages.editbuilding', compact('building', 'checkboxAmenities', 'additionalAmenities'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'building_name' => 'required|string',
            'no_of_floors' => 'required|integer|min:1',
            'building_address' => 'required|string',
            'street' => 'required|string',
            'city' => 'required|string',
            'pin_code' => 'required|integer',
            'state' => 'required|string',
            'country' => 'required|string',
            'super_built_up_area' => 'required|integer',
            'carpet_area' => 'required|integer',
        ]);

        $checkboxAmenities = implode(',', $request->input('building_amenities', []));

        DB::table('building')->where('id', $id)->update([
            'building_name' => $validatedData['building_name'],
            'no_of_floors' => $validatedData['no_of_floors'],
            'building_address' => $validatedData['building_address'],
            'street' => $validatedData['street'],
            'city' => $validatedData['city'],
            'pin_code' => $validatedData['pin_code'],
            'state' => $validatedData['state'],
            'country' => $validatedData['country'],
            'super_built_up_area' => $validatedData['super_built_up_area'],
            'carpet_area' => $validatedData['carpet_area'],
            'building_amenities' => $checkboxAmenities,
            'additional_amenities' => $request->input('additional_amenities'),
            'updated_at' => now(),
        ]);

        return redirect()->route('buildings.index')->with('success', 'Building updated successfully');
    }

    public function destroy($id)
    {
        DB::table('building')->where('id', $id)->delete();

        return redirect()->route('buildings.index')->with('success', 'Building soft deleted successfully');
    }

    public function buildingpage()
    {
        $buildings = DB::table('building')->get();
        $rooms = Room::all(); 
        return view('pages.building', compact('buildings', 'rooms'));
    }

    public function show($id)
    {
        // Fetch the rooms associated with the building
        $rooms = Room::where('building_id', $id)->get();
        
        // Define the $page variable
        $page = 'rooms';
        
        // Return the view with room data and the $page variable
        return view('rooms.show', compact('rooms', 'page'));
    }

    public function showRooms($buildingId)
    {
        $building = Building::findOrFail($buildingId);
        $rooms = $building->rooms()->whereNull('deleted_at')->get();
    
        return view('rooms.show', compact('building', 'rooms'));
    }
}
