<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Room; 
use App\Models\Building; 
use App\Models\Sale;


class BuildingController extends Controller
{
    public function showRooms($building_id, Request $request)
{

    $building = Building::findOrFail($building_id);
    

    $rooms = Room::where('building_id', $building_id)->get();
    

    if ($request->has('customer_id')) {
        $customer_id = $request->input('customer_id');

        $customerNames = Sale::whereIn('room_id', $rooms->pluck('id'))
                            ->where('customer_id', $customer_id)
                            ->pluck('customer_name');
    } else {

        $customerNames = null;
    }


    return view('rooms.show', compact('building', 'rooms', 'customerNames'));
}

public function index()
{
    $buildings = Building::all(); 

    $buildings = $buildings->map(function($building) {
        // Decode JSON string into an array
        $amenities = json_decode($building->amenities, true);

        // Ensure amenities is an array before formatting
        if (!is_array($amenities)) {
            $amenities = [];
        }

        // Format amenities
        $formattedAmenities = array_map(function($amenity) {
            return $amenity['name'] . ' (' . $amenity['type'] . ')';
        }, $amenities);
        
        // Add formatted amenities to the building model
        $building->formatted_amenities = implode(', ', $formattedAmenities);

        return $building;
    });

    return view('pages.buildingdashboard', compact('buildings'));
}


    public function create()
    {
        return view('pages.addbuilding');
    }

    public function store(Request $request)
{
    // Validate the request data
    $validatedData = $request->validate([
        'building_name' => 'required|string',
        'no_of_floors' => 'required|integer|min:1',
        'building_address' => 'required|string',
        'street' => 'required|string',
        'city' => 'required|string',
        'pin_code' => 'required|integer',
        'state' => 'required|string',
        'country' => 'required|string',
        'super_built_up_area_sq_m' => 'required|numeric|min:0', // Super Buildup Area (sq m)
        'carpet_area_sq_m' => 'required|numeric|min:0', // Carpet Area (sq m)
        'super_built_up_area' => 'required|numeric|min:0', // Super Buildup Area (sq ft)
        'carpet_area' => 'required|numeric|min:0', // Carpet Area (sq ft)
    ]);

    // Process the amenities
    $amenities = [];
    if ($request->has('amenities')) {
        foreach ($request->input('amenities') as $amenity) {
            $amenities[] = [
                'name' => $amenity['name'],
                'type' => $amenity['type'],
            ];
        }
    }

    // Insert data into the buildings table
    DB::table('buildings')->insert([
        'building_name' => $validatedData['building_name'],
        'no_of_floors' => $validatedData['no_of_floors'],
        'building_address' => $validatedData['building_address'],
        'street' => $validatedData['street'],
        'city' => $validatedData['city'],
        'pin_code' => $validatedData['pin_code'],
        'state' => $validatedData['state'],
        'country' => $validatedData['country'],
        'super_built_up_area_sq_m' => $validatedData['super_built_up_area_sq_m'], // Super Buildup Area (sq m)
        'carpet_area_sq_m' => $validatedData['carpet_area_sq_m'], // Carpet Area (sq m)
        'super_built_up_area' => $validatedData['super_built_up_area'], // Super Buildup Area (sq ft)
        'carpet_area' => $validatedData['carpet_area'], // Carpet Area (sq ft)
        'amenities' => json_encode($amenities), // Store the amenities as JSON
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect()->route('admin.dashboard')->with('success', 'Building created successfully.');
}

    
    public function edit($id)
    {
        $building = DB::table('buildings')->where('id', $id)->first();

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

        DB::table('buildingS')->where('id', $id)->update([
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

        return redirect()->route('admin.building')->with('success', 'Building updated successfully');
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
   // app/Http/Controllers/BuildingController.php

public function show($id)
{
    // Fetch the building by ID
    $building = Building::findOrFail($id);

    $amenities = json_decode($building->amenities, true);
    
    // Format amenities
    $formattedAmenities = array_map(function($amenity) {
        return $amenity['name'] . ' (' . $amenity['type'] . ')';
    }, $amenities);
    
    // Add formatted amenities to the building model
    $building->formatted_amenities = implode(', ', $formattedAmenities);

    // Fetch rooms related to the building
    $rooms = $building->rooms;

    // Fetch room types
    $roomTypes = $building->rooms()->distinct()->pluck('room_type')->toArray();

    // Pass data to the view
    return view('pages.buildingdetails', [
        'building' => $building,
        'rooms' => $rooms,
        'roomTypes' => $roomTypes,
        'selectedBuildingName' => $building->name,
    ]);
}

}
