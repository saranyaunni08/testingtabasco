<?php

namespace App\Http\Controllers;

use App\Models\Parking;
use Illuminate\Http\Request;

class ParkingController extends Controller
{
    public function index()
    {
        $parkings = Parking::all();
        $page = 'parking index';
        $title = 'parking index';
        return view('parking.index', compact('parkings','page','title'));
    }

    public function create()
    {
        $page = 'parking create';
        $title = 'parking create';
        return view('parking.create',compact('page','title',));
    }

    public function store(Request $request)
    {
        $page = 'parking create';
        $title = 'parking create';
        $request->validate([
            'slot_number' => 'required|unique:parkings,slot_number',
            'floor_number' => 'required|integer',
            'status' => 'required|in:available,occupied',
            'amount' => 'required|numeric',

        ]);

        Parking::create($request->all());

        return redirect()->route('admin.parking.index',compact('page','title'))->with('success', 'Parking slot created successfully.');
    }
    
    public function edit(Parking $parking)
    {
        $page = 'parking edit';
        $title = 'parking edit';
        return view('parking.edit', compact('parking','page','title'));
    }
    public function update(Request $request, Parking $parking)
    {
        $request->validate([
            'slot_number' => 'required|unique:parkings,slot_number,' . $parking->id,
            'floor_number' => 'required|integer',
            'status' => 'required|in:available,occupied',
            'amount' => 'required|numeric',
        ]);
    
        // Set purchaser_name to null if status is "available"
        $data = $request->all();
        if ($request->status === 'available') {
            $data['purchaser_name'] = null;
        }
    
        $parking->update($data);
    
        return redirect()->route('admin.parking.index')->with('success', 'Parking slot updated successfully.');
    }
    
    public function destroy(Parking $parking)
    {
        $parking->delete();

        return redirect()->route('admin.parking.index')->with('success', 'Parking slot deleted successfully.');
    }
}
