<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AdminController extends Controller
{
    public function index()
    {
        return view('pages.dashboard');
    }

    public function addbuilding()
    {
        return view('pages.addbuilding');
    }

    public function buildingpage()
    {
        $buildings = DB::table('building')->get();
        return view('pages.building', compact('buildings'));
    }
    public function buildingstore(Request $request)
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
    
        // Process amenities checkboxes
        $checkboxAmenities = $request->input('building_amenities', []);
    
        // Convert the array of amenities into a comma-separated string
        $amenities = implode(',', $checkboxAmenities);
    
        // Get the value of additional amenities
        $additionalAmenities = $request->input('additional_amenities');
    
        // Insert data into the 'building' table
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
            'building_amenities' => $amenities,
            'additional_amenities' => $additionalAmenities, 
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    
      
        return redirect()->route('admin.building')->with('success', 'Building added successfully');
    }
    
    
public function editbuilding($id)
{
    
    $building = DB::table('building')->where('id', $id)->first();


    $amenities = explode(',', $building->building_amenities);

    // Initialize an array to store checkbox amenities
    $checkboxAmenities = [];

    // Loop through the amenities
    foreach ($amenities as $amenity) {
        // Check if the amenity is one of the predefined ones
        if (in_array($amenity, ['Parking', 'Gym', 'Swimming Pool'])) {
            $checkboxAmenities[] = $amenity;
        }
    }

    // Pass the additional amenities to the view
    $additionalAmenities = $building->additional_amenities;

   
    return view('pages.editbuilding', compact('building', 'checkboxAmenities', 'additionalAmenities'));
}
public function updatebuilding(Request $request, $id)
{
    // Validate the incoming data
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

    // Process and format the amenities checkboxes
    $checkboxAmenities = implode(',', $request->input('building_amenities', []));

    // Update the building record
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

    // Redirect the user with a success message
    return redirect()->route('admin.building')->with('success', 'Building updated successfully');
}


    public function destroy($id)
    {
        DB::table('building')->where('id', $id)->delete();
    
        return redirect()->route('admin.building')->with('success', 'Building soft deleted successfully');
    }
    public function addfloor()
    {
        return view('pages.floor');
    }

}
