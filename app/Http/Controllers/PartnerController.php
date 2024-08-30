<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Partner;

class PartnerController extends Controller
{
    public function create()
    {
        $title = 'Add New Partner';

        return view('partners.create', compact('title'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:partners,email',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|max:10',
            'nationality' => 'nullable|string|max:100',
            'identification_number' => 'nullable|string|max:100',
        ]);

        Partner::create($validated);

        return redirect()->route('admin.partners.create')->with('success', 'Partner added successfully!');
    }
}
