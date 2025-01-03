<?php

namespace App\Http\Controllers;

use App\Models\banks;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function create(){
        $title = 'Add Bank';
        $page = 'Add Bank';
         //Return view with the data
         return view('banks.create',compact(
            
            'title',
            'page'
        ));
    }
    // In your BankController
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'account_number' => 'required|string|max:20',
        'account_holder_name' => 'required|string|max:255',
        'ifsc_code' => 'required|string|max:20',
        'branch' => 'required|string|max:255',
        'address' => 'nullable|string|max:255',
        'city' => 'nullable|string|max:255',
        'country' => 'nullable|string|max:255',
        'contact_number' => 'nullable|string|max:15',
        'email_address' => 'nullable|email|max:255',
    ]);

    $bank = new banks();
    $bank->name = $validated['name'];
    $bank->account_number = $validated['account_number'];
    $bank->account_holder_name =$validated['account_holder_name'];
    $bank->ifsc_code = $validated['ifsc_code'];
    $bank->branch = $validated['branch'];
    $bank->address = $validated['address'];
    $bank->city = $validated['city'];
    $bank->country = $validated['country'];
    $bank->contact_number = $validated['contact_number'];
    $bank->email_address = $validated['email_address'];
   
    
    $bank->save();

    return redirect()->route('admin.banks.create')->with('success', 'Bank added successfully!');
}

public function views(){
    $banks = banks::all();
    $title = 'Bank List';
    $page = 'bank list';
    return view('banks.views',compact(
     'banks',
     'title',
     'page',
    ));
}

public function edit($id) {
    $bank = banks::findOrFail($id);  // Retrieve the bank by its ID
    $title = 'Edit Bank Details';
    $page = 'edit bank details';

    // Pass the bank details to the view
    return view('banks.edit', compact('title', 'page', 'bank'));
}

public function update(Request $request, $id)
{
    // Validate the incoming data
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'account_number' => 'required|string|max:20',
        'account_holder_name' => 'required|string|max:255',
        'ifsc_code' => 'required|string|max:20',
        'branch' => 'required|string|max:255',
        'address' => 'nullable|string|max:255',
        'city' => 'nullable|string|max:255',
        'country' => 'nullable|string|max:255',
        'contact_number' => 'nullable|string|max:15',
        'email_address' => 'nullable|email|max:255',
    ]);

    // Find the bank record by its ID
    $bank = banks::findOrFail($id);

    // Update the bank details
    $bank->name = $validated['name'];
    $bank->account_number = $validated['account_number'];
    $bank->account_holder_name = $validated['account_holder_name'];
    $bank->ifsc_code = $validated['ifsc_code'];
    $bank->branch = $validated['branch'];
    $bank->address = $validated['address'] ?? $bank->address; // Use the existing address if not provided
    $bank->city = $validated['city'];
    $bank->country = $validated['country'];
    $bank->contact_number = $validated['contact_number'];
    $bank->email_address = $validated['email_address'];

    // Save the updated bank record
    $bank->save();

    // Redirect back with a success message
    return redirect()->route('admin.banks.views')->with('success', 'Bank updated successfully!');
}



}



