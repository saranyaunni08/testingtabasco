<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Room;

class SalesController extends Controller
{

    public function create(Request $request)
    {

        $roomId = $request->query('room_id');


        $room = Room::find($roomId);


        if (!$room) {

            return redirect()->back()->with('error', 'Room not found.');
        }


        return view('pages.sales', ['room' => $room]);
    }


    public function store(Request $request)
    {
        try {

            // dd($request->all());

        $validatedData = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_address' => 'required|string|max:255',
            'customer_street' => 'required|string|max:255',
            'customer_city' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_pin' => 'required|string|max:10',
            'customer_state' => 'required|string|max:255',
            'customer_country' => 'required|string|max:255',
            'sale_amount' => 'required|numeric',
            'discount_amount' => 'required|numeric',
            'advance_amount' => 'nullable|numeric',
            'payment_method' => 'required|string|max:255',
            'installment_period' => 'nullable|string|max:255',
            'installment_amount' => 'nullable|numeric',
            'payment_using' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'branch' => 'nullable|string|max:255',
            'account_num' => 'nullable|string|max:255',
            'ifsc' => 'nullable|string|max:255',
            'room_id' => 'required|exists:rooms,id',
        ]);


        $sale = new Sale();


        $sale->customer_name = $request->input('customer_name');
        $sale->customer_address = $request->input('customer_address');
        $sale->customer_street = $request->input('customer_street');
        $sale->customer_city = $request->input('customer_city');
        $sale->customer_phone = $request->input('customer_phone');
        $sale->customer_pin = $request->input('customer_pin');
        $sale->customer_state = $request->input('customer_state');
        $sale->customer_country = $request->input('customer_country');
        $sale->sale_amount = $request->input('sale_amount');
        $sale->discount_amount = $request->input('discount_amount');
        $sale->advance_amount = $request->input('advance_amount');
        $sale->payment_method = $request->input('payment_method');
        $sale->installment_period = $request->input('installment_period');
        $sale->installment_amount = $request->input('installment_amount');
        $sale->payment_using = $request->input('payment_using');
        $sale->bank_name = $request->input('bank_name');
        $sale->branch = $request->input('branch');
        $sale->account_num = $request->input('account_num');
        $sale->ifsc = $request->input('ifsc');
        $sale->room_id = $request->input('room_id'); 

        $sale->save();

        $room = Room::find($validatedData['room_id']);
        $room->status = 'sold';
        $room->save();


        return redirect()->route('building.show', ['rooms' => $validatedData['room_id']])->with('success', 'Sale created successfully!');

    } catch (\Exception $e) {

        \Log::error($e->getMessage());
    

        return redirect()->back()->with('error', 'Failed to create sale. Please try again.');
}
    
}
}