<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Building;

class DashboardController extends Controller
{
    public function index()
    {
        // Assuming you're fetching buildings from the database
        $buildings = Building::all();

        // Pass the $buildings variable to the view
        return view('pages.dashboard', ['buildings' => $buildings]);
    }

    
}
