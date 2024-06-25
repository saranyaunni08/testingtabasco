@extends('layouts.default', ['title' => 'Customer Details'])

@section('content')
@php
    $page = $page ?? 'default-page'; // Provide a default value for $page
@endphp
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-info shadow-info border-radius-lg p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="pe-3">
                                    <h2 class="text-white">{{ strtoupper($sales[0]->customer_name) }}</h2>
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
                                            <div class="col-md-4 fs-5"><strong>Room Model:</strong> {{ $sale->room->room_type }}</div>  
                                        </div>
                                        <div class="row mb-3">
                                            @if ($sale->room->room_type === 'Flat')
                                            <div class="row mb-3">
                                            <div class="col-md-4 fs-5"><strong>Super Build Up Area in sq ft:</strong> {{ $sale->room->flat_build_up_area }}</div>
                                            <div class="col-md-4 fs-5"><strong>Super Build Up Area Price per sq ft:</strong> {{ $sale->room->flat_super_build_up_price }}</div>
                                            <div class="col-md-4 fs-5"><strong>Carpet Area in sq ft:</strong> {{ $sale->room->flat_carpet_area }}</div>
                                        </div>
                                        <div class="row mb-3">

                                            <div class="col-md-4 fs-5"><strong>Carpet Area Price per sq ft:</strong> {{ $sale->room->flat_carpet_area_price }}</div>
                                                @if ($sale->area_calculation_type === 'carpet_area_rate')
                                                    <div class="col-md-4 fs-5"><strong>Flat Expected Rate :</strong> {{ $sale->room->flat_expected_carpet_area_price }}</div>
                                                    @elseif ($sale->area_calculation_type === 'built_up_area_rate')
                                                    <div class="col-md-4 fs-5"><strong>flat Expected Rate :</strong> {{ $sale->room->flat_expected_super_buildup_area_price }}</div>
                                                @endif

                                                @if ($sale->calculation_type == 'fixed_amount')
                                                <div class="col-md-4 fs-5"><strong>Parking:</strong>  Unparked</div>
                                                    @elseif ( $sale->calculation_type == 'rate_per_sq_ft')
                                                    <div class="col-md-4 fs-5"><strong>Parking Sq Ft:</strong> {{ $sale->total_sq_ft_for_parking }}</div>
                                                    <div class="col-md-4 fs-5"><strong>Parking Rate (per sq ft) :</strong> {{ $sale->parking_rate_per_sq_ft }}</div>
                                                @endif
                                            </div>
                                            <div class="row mb-3">

                                            @elseif ($sale->room->room_type === 'Shops')
                                            <div class="col-md-4 fs-5"><strong>Shop Type:</strong> {{ $sale->room->shop_type }}</div>
                                            <div class="col-md-4 fs-5"><strong>Super Build Up Area in sq ft:</strong> {{ $sale->room->build_up_area }}</div>
                                            <div class="col-md-4 fs-5"><strong>Super Build Up Area Price per sq ft:</strong> {{ $sale->room->super_build_up_price }}</div>
                                            <div class="col-md-4 fs-5"><strong>Carpet Area in sq ft:</strong> {{ $sale->room->carpet_area }}</div>
                                            <div class="col-md-4 fs-5"><strong>Carpet Area Price per sq ft:</strong> {{ $sale->room->carpet_area_price }}</div>
                                                @if ($sale->area_calculation_type === 'carpet_area_rate')
                                                    <div class="col-md-4 fs-5"><strong> Expected Rate :</strong> {{ $sale->room->expected_carpet_area_price }}</div>
                                                    @elseif ($sale->area_calculation_type === 'built_up_area_rate')
                                                    <div class="col-md-4 fs-5"><strong> Expected Rate :</strong> {{ $sale->room->expected_super_buildup_area_price }}</div>
                                                @endif

                                                @if ($sale->calculation_type == 'fixed_amount')
                                                <div class="col-md-4 fs-5"><strong>Parking:</strong>  Unparked</div>
                                                    @elseif ( $sale->calculation_type == 'rate_per_sq_ft')
                                                    <div class="col-md-4 fs-5"><strong>Parking Sq Ft:</strong> {{ $sale->total_sq_ft_for_parking }}</div>
                                                    <div class="col-md-4 fs-5"><strong>Parking Rate (per sq ft) :</strong> {{ $sale->parking_rate_per_sq_ft }}</div>
                                                @endif


                                            @elseif ($sale->room->room_type === 'Table space')
                                            <div class="col-md-4 fs-5"><strong>Type:</strong> {{ $sale->room->space_type }}</div>
                                            <div class="col-md-4 fs-5"><strong>Space Area :</strong> {{ $sale->room->space_area }}</div>
                                            <div class="col-md-4 fs-5"><strong>Space Rate:</strong> {{ $sale->room->space_rate }}</div>
                                                @if ($sale->area_calculation_type === 'carpet_area_rate')
                                                    <div class="col-md-4 fs-5"><strong> Expected Rate :</strong> {{ $sale->room->space_expected_price }}</div>
                                                    @elseif ($sale->area_calculation_type === 'built_up_area_rate')
                                                    <div class="col-md-4 fs-5"><strong> Expected Rate :</strong> {{ $sale->room->space_expected_price }}</div>
                                                @endif

                                                @if ($sale->calculation_type == 'fixed_amount')
                                                <div class="col-md-4 fs-5"><strong>Parking:</strong>  Unparked</div>
                                                    @elseif ( $sale->calculation_type == 'rate_per_sq_ft')
                                                    <div class="col-md-4 fs-5"><strong>Parking Sq Ft:</strong> {{ $sale->total_sq_ft_for_parking }}</div>
                                                    <div class="col-md-4 fs-5"><strong>Parking Rate (per sq ft) :</strong> {{ $sale->parking_rate_per_sq_ft }}</div>
                                                @endif



                                            @elseif ($sale->room->room_type === 'Chair space')
                                            <div class="col-md-4 fs-5"><strong>Chair Name:</strong> {{ $sale->room->chair_name }}</div>
                                            <div class="col-md-4 fs-5"><strong>Chair Type :</strong> {{ $sale->room->chair_type }}</div>
                                            <div class="col-md-4 fs-5"><strong>Chair Material:</strong> {{ $sale->room->chair_material }}</div>
                                            <div class="col-md-4 fs-5"><strong>Chair space in sq :</strong> {{ $sale->room->chair_space_in_sq }}</div>
                                            <div class="col-md-4 fs-5"><strong>Chair space Rate in sq :</strong> {{ $sale->room->chair_space_in_sq }}</div>
                                            <div class="col-md-4 fs-5"><strong>Chair space Expected Rate :</strong> {{ $sale->room->chair_space_expected_rate }}</div>
                                            @endif



                                        </div>
                                        <div class="row mb-3">
                                           
                                            <div class="col-md-4 fs-5"><strong>Sale Amount in Sq:</strong> {{ number_format($sale->sale_amount) }}</div>
                                            
                                        </div>
                                     
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
