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
            'building_address' => 'required|string',
            'no_of_floors' => 'required|integer|min:1',
            'building_amenities' => 'nullable|array',
            'building_amenities.*' => 'string',
        ]);

        DB::table('building')->insert([
            'building_name' => $validatedData['building_name'],
            'building_address' => $validatedData['building_address'],
            'no_of_floors' => $validatedData['no_of_floors'],
            'building_amenities' => $validatedData['building_amenities'] ? implode(',', $validatedData['building_amenities']) : null,
        ]);

        return redirect()->route('admin.building')->with('success', 'Building added successfully');
    }
    public function destroy($id)
    {
        DB::table('building')->where('id', $id)->delete();
    
        return redirect()->route('admin.building')->with('success', 'Building soft deleted successfully');
    }
    

}
