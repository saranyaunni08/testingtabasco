<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index() {
        return view('pages.dashboard');
    }

    public function show() {
        return view('pages.building');
    }

    public function view() {
        return view('pages.sales');
    }
}