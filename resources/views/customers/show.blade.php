<!-- resources/views/customers/show.blade.php -->

@extends('layouts.default', ['title' => 'Customer Details'])

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-info shadow-info border-radius-lg p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="pe-3">
                                    <h3>{{ strtoupper($customerName) }}</h3> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-2">
                        <div class="row p-4">
                            @foreach($sales as $index => $sale)
                                <div class="col-md-6 mb-4">
                                    <div class="card">
                                        <div class="card-header bg-gradient-info">
                                            <h5 class="text-white">{{ $sale->room_details['room_number'] }}</h5>
                                        </div>
                                        <div class="card-body">
                                            <p><strong>Building Name:</strong> {{ $sale->room->building->building_name }}</p>
                                            <p><strong>Customer Name:</strong> {{ strtoupper($sale->customer_name) }}</p>
                                            <p><strong>Contact:</strong> {{ $sale->customer_contact }}</p>
                                            <p><strong>Email:</strong> {{ $sale->customer_email }}</p>
                                            <p><strong>Flat Model:</strong> {{ $sale->room->flat_model }}</p>
                                            <p><strong>Total Sq Ft:</strong> {{ $sale->room->total_sq_ft }}</p>
                                            <p><strong>Total Sq Ft Rate:</strong> {{ $sale->room->total_sq_rate }}</p>
                                            <p><strong>Expected Amount:</strong> {{ $sale->room->expected_amount }}</p>
                                            <p><strong>Sale Amount:</strong> {{ $sale->sale_amount }}</p>
                                            <p><strong>Calculation Type:</strong> {{ $sale->calculation_type }}</p>
                                            <p><strong>Parking Amount:</strong> {{ $sale->parking_amount }}</p>
                                            <p><strong>GST Percentage:</strong> {{ $sale->gst_percent }}</p>
                                            <p><strong>Advance Payment:</strong> {{ $sale->advance_payment }}</p>
                                            @if ($sale->advance_payment === 'bank_transfer')
                                                <p><strong>Transfer ID:</strong> {{ $sale->transfer_id }}</p>
                                            @elseif ($sale->advance_payment === 'later')
                                                <p><strong>Last Due Date:</strong> {{ $sale->last_date }}</p>
                                            @endif
                                            <p><strong>Discount Percent:</strong> {{ $sale->discount_percent }}</p>
                                            <p><strong>Total Amount:</strong> {{ $sale->total_amount ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
