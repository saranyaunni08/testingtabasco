<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;


use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('pages.dashboard');
    }

    public function view()
    {
        return view('pages.sales');
    }

    public function addfloor()
    {
        return view('pages.floor');
    }

    public function buildingpage()
    {
        $buildings = DB::table('building')->get();
        $rooms = Room::all(); 
        return view('pages.building', compact('buildings', 'rooms'));
    }
    
}
