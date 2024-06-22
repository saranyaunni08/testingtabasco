@extends('layouts.default', ['title' => 'Customer Details'])

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-info shadow-info border-radius-lg p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="pe-3">
                                    <h2 class="text-white">{{ strtoupper($customerName) }}</h2>
                                </div>
                                <div>
                                    <button class="btn btn-light text-info">
                                        <i class="fas fa-print"></i> Print
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-2">
                        @foreach($sales as $sale)
                            <div class="col-12 mb-4">
                                <div class="card border-0 shadow">
                                    <div class="card-header bg-info text-white">
                                        <h3 style="text-transform: capitalize">{{ $sale->room->room_number }}</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-md-4 fs-5"><strong>Building Name:</strong> {{ $sale->room->building->building_name }}</div>
                                            <div class="col-md-4 fs-5"><strong>Contact:</strong> {{ $sale->customer_contact }}</div>
                                            <div class="col-md-4 fs-5"><strong>Email:</strong> {{ $sale->customer_email }}</div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-4 fs-5"><strong>Flat Model:</strong> {{ $sale->room->room_type }}</div>
                                            <div class="col-md-4 fs-5"><strong>Build Up Area:</strong> {{ $sale->room->build_up_area }}</div>
                                            <div class="col-md-4 fs-5"><strong>Carpet Area:</strong> {{ $sale->room->carpet_area }}</div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-4 fs-5"><strong>Expected Amount:</strong> 
                                                @if ($sale->area_calculation_type === 'carpet_area_rate')
                                                    {{ number_format($sale->expected_carpet_area_price) }}
                                                @elseif ($sale->area_calculation_type === 'build_up_area_price')
                                                    {{ number_format($sale->expected_super_build_up_area_price) }}
                                                @else
                                                    N/A
                                                @endif
                                            </div>
                                            <div class="col-md-4 fs-5"><strong>Sale Amount in Sq:</strong> {{ number_format($sale->sale_amount) }}</div>
                                            <div class="col-md-4 fs-5"><strong>Parking:</strong>
                                                @if ($sale->calculation_type === 'rate_per_sq_ft')
                                                    Rate Per Sq Ft
                                                @elseif ($sale->calculation_type === 'fixed_amount')
                                                    Fixed Amount
                                                @else
                                                    N/A
                                                @endif
                                            </div>
                                        </div>
                                        @if ($sale->calculation_type === 'rate_per_sq_ft')
                                            <div class="row mb-3">
                                                <div class="col-md-4 fs-5"><strong>Parking Rate Per Sq Ft:</strong> {{ number_format($sale->parking_rate_per_sq_ft) }}</div>
                                                <div class="col-md-4 fs-5"><strong>Total Parking Sq ft:</strong> {{ number_format($sale->total_sq_ft_for_parking) }}</div>
                                            </div>
                                        @endif
                                        <div class="row mb-3">
                                            <div class="col-md-4 fs-5"><strong>Parking Amount:</strong> {{ number_format($sale->parking_amount) }}</div>
                                            <div class="col-md-4 fs-5"><strong>Room Rate:</strong> {{ number_format($sale->room_rate) }}</div>
                                            <div class="col-md-4 fs-5"><strong>GST Percentage:</strong> {{ $sale->gst_percent }}%</div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-4 fs-5"><strong>Total With GST:</strong> {{ $sale->total_with_gst }}</div>
                                            <div class="col-md-4 fs-5"><strong>Discount Percent:</strong> {{ $sale->discount_percent }}%</div>
                                            <div class="col-md-4 fs-5"><strong>Total With Discount:</strong> {{ $sale->total_with_discount }}</div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-4 fs-5"><strong>Advance Payment:</strong> {{ $sale->advance_amount }}</div>
                                            <div class="col-md-4 fs-5"><strong>Remaining Balance:</strong> {{ $sale->remaining_balance }}</div>
                                        </div>
                                        @if ($sale->advance_payment === 'bank_transfer')
                                            <div class="row mb-3">
                                                <div class="col-md-4 fs-5"><strong>Transfer ID:</strong> {{ $sale->transfer_id }}</div>
                                            </div>
                                            @elseif ($sale->advance_payment === 'later')
                                            <div class="row mb-3">
                                                @isset($master_settings)
                                                    @foreach ($master_settings as $master_setting)
                                                        <div class="col-md-4 fs-5"><strong>Last Due Date:</strong> {{ $master_setting->advance_payment_days }}</div>
                                                    @endforeach
                                                @endisset
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
