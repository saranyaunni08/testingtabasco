@extends('layouts.default', ['title' => 'Customer Details'])

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            @if ($sales->isNotEmpty())
                @foreach ($sales as $sale)
                    <h5 class="mb-0" style="text-transform:capitalize">{{ $sale->customer_name }}</h5>
                @endforeach
            @else
                <h5 class="mb-0">No sales data available</h5>
            @endif
        </div>
        <div class="card-body">
            @if ($sales->isNotEmpty())
                <div class="table-responsive">
                    <table id="customerDetailsTable" class="table table-bordered table-hover table-striped" style="width:100%">
                        <thead class="table-dark">
                            <tr>
                                <th>Building Name</th>
                                <th>Contact</th>
                                <th>Email</th>
                                <th>Room Model</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sales as $sale)
                                <tr>
                                    <td style="text-transform:capitalize;color:black;">{{ $sale->room->building->building_name }}</td>
                                    <td style="color:black;">{{ $sale->customer_contact }}</td>
                                    <td style="color:black;">{{ $sale->customer_email }}</td>
                                    <td style="color:black;">{{ $sale->room->room_type }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <div class="card mt-3">
                                            <div class="card-header">
                                                <strong style="text-transform: capitalize">{{ $sale->room->room_type }} Details</strong>
                                            </div>
                                            <div class="card-body">
                                                <table class="table table-sm table-borderless">
                                                    <tbody>
                                                        @if ($sale->room->room_type === 'Flat')
                                                            <tr><td><strong style="color:black;">Room Number:</strong></td><td style="color:black; text-transform: capitalize">{{ $sale->room->room_number }}</td></tr>
                                                            <tr><td><strong style=" color:black;">Flat Model:</strong></td><td style="color:black; ">{{ $sale->room->flat_model }}</td></tr>
                                                            @if ($sale->area_calculation_type === 'carpet_area_rate')
                                                                <tr><td><strong style="color:black;">Carpet Area sq ft:</strong></td><td style="color:black;">{{ $sale->room->flat_carpet_area }} sq ft</td></tr>
                                                                <tr><td><strong style="color:black;">Carpet Area Price per sq ft:</strong></td><td style="color:black;">₹{{ $sale->room->flat_carpet_area_price }}</td></tr>
                                                                <tr><td><strong style="color:black;">Expected Amount:</strong></td><td style="color:black;">₹{{ $sale->room->flat_expected_carpet_area_price }}</td></tr>
                                                            @elseif ($sale->area_calculation_type === 'built_up_area_rate')
                                                                <tr><td><strong style="color:black;">Super Build Up sq ft:</strong></td><td style="color:black;">{{ $sale->room->flat_build_up_area }}</td></tr>
                                                                <tr><td><strong style="color:black;">Super Build Up Price per sq ft:</strong></td><td style="color:black;">₹{{ $sale->room->flat_super_build_up_price }}</td></tr>
                                                                <tr><td><strong style="color:black;">Expected Amount:</strong></td><td style="color:black;">₹{{ $sale->room->flat_expected_super_buildup_area_price }}</td></tr>
                                                            @endif
                                                            <tr><td><strong style="color:black;">Sale Amount:</strong></td><td style="color:black;">₹{{ $sale->sale_amount }}</td></tr>
                                                            <tr><td><strong style="color:black;">Total Amount:</strong></td><td style="color:black;">₹{{ $sale->total_amount }}</td></tr>
                                                            <tr><td><strong style="color:black;">Advance Amount:</strong></td><td style="color:black;">₹{{ $sale->advance_amount}}</td></tr>
                                                            <tr><td><strong style="color:black;">Partner Name:</strong></td><td style="color:black;">{{ $sale->partner_name}}</td></tr>
                                                            
                                                            <tr><td><strong style="color:black;">GST %:</strong></td><td style="color:black;">{{ $sale->gst_percent }}%</td></tr>
                                                            <tr><td><strong style="color:black;">GST Amount:</strong>
                                                                <td style="color:black;">₹{{ number_format($sale->total_with_gst - $sale->room_rate, 2) }}</td></tr>
                                                            <tr><td><strong style="color:black;">Total With GST:</strong></td><td style="color:black;">₹{{ $sale->total_with_gst }}</td></tr>

                                                            <tr><td><strong style="color:black;">Discount %:</strong></td><td style="color:black;">₹{{ $sale->discount_percent}}</td></tr>
                                                            <tr><td><strong style="color:black;">Discount Amount:</strong></td><td style="color:black;">₹{{$sale->total_with_gst - $sale->total_with_discount }}</td></tr>
                                                            <tr><td><strong style="color:black;">Parking Amount:</strong></td><td style="color:black;">₹{{ $sale->parking_amount }}</td></tr>
                                                            <tr>
                                                                <td><strong style="color:black;">No of Installments:</strong></td>
                                                                <td id="remaining-installments-{{ $sale->id }}" style="color:black;">{{ $sale->installments }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong style="color:black;">Installment Date:</strong></td>
                                                                <td id="remaining-installments-{{ $sale->id }}" style="color:black;">{{ $sale->installment_date }}</td>
                                                            </tr>
                                                            <tr><td><strong style="color:black;">Amount per Installment:</strong></td>
                                                            <td style="color:black;">₹{{ $sale->installments > 0 ? number_format($sale->remaining_balance / $sale->installments, 2) : 'N/A' }}</td></tr>
                                                            <tr style="display: none;">
                                                                <td><strong style="color:black;">Remaining Installments Total:</strong></td>
                                                                <td id="remaining-installments-total">{{ count($sales) }}</td>
                                                            </tr>
                                                            <tr><td><strong style="color:black;">Remaining Balance:</strong></td><td style="color:black;">₹{{ $sale->remaining_balance}}</td></tr>
                                                            <tr>
                                                                <td><strong style="color:black;">Remaining Balance after paid Installments:</strong></td>
                                                                <td style="color:black;">₹{{ $remainingBalanceAfterInstallments }}</td>
                                                            </tr>
                                                        @elseif ($sale->room->room_type === 'Shops')

                                                            
                                                            <tr><td><strong style="color:black;">Room Number:</strong></td><td style="color:black; text-transform: capitalize">{{ $sale->room->room_number }}</td></tr>
                                                            <tr><td><strong style=" color:black;">Shop Type:</strong></td><td style="color:black; ">{{ $sale->room->shop_type }}</td></tr>
                                                            @if ($sale->area_calculation_type === 'carpet_area_rate')
                                                                <tr><td><strong style="color:black;">Carpet Area sq ft:</strong></td><td style="color:black;">{{ $sale->room->carpet_area }} sq ft</td></tr>
                                                                <tr><td><strong style="color:black;">Carpet Area Price per sq ft:</strong></td><td style="color:black;">₹{{ $sale->room->carpet_area_price }}</td></tr>
                                                                <tr><td><strong style="color:black;">Expected Amount:</strong></td><td style="color:black;">₹{{ $sale->room->expected_carpet_area_price }}</td></tr>
                                                            @elseif ($sale->area_calculation_type === 'built_up_area_rate')
                                                                <tr><td><strong style="color:black;">Super Build Up sq ft:</strong></td><td style="color:black;">{{ $sale->room->build_up_area }}</td></tr>
                                                                <tr><td><strong style="color:black;">Super Build Up Price per sq ft:</strong></td><td style="color:black;">₹{{ $sale->room->super_build_up_price }}</td></tr>
                                                                <tr><td><strong style="color:black;">Expected Amount:</strong></td><td style="color:black;">₹{{ $sale->room->expected_super_buildup_area_price }}</td></tr>
                                                            @endif
                                                            <tr><td><strong style="color:black;">Sale Amount:</strong></td><td style="color:black;">₹{{ $sale->sale_amount }}</td></tr>
                                                            <tr><td><strong style="color:black;">Total Amount:</strong></td><td style="color:black;">₹{{ $sale->total_amount }}</td></tr>
                                                            
                                                            <tr><td><strong style="color:black;">GST %:</strong></td><td style="color:black;">{{ $sale->gst_percent }}%</td></tr>
                                                            <tr><td><strong style="color:black;">GST Amount:</strong>
                                                                <td style="color:black;">₹{{ number_format($sale->total_with_gst - $sale->room_rate, 2) }}</td></tr>
                                                            <tr><td><strong style="color:black;">Total With GST:</strong></td><td style="color:black;">₹{{ $sale->total_with_gst }}</td></tr>

                                                            <tr><td><strong style="color:black;">Discount %:</strong></td><td style="color:black;">₹{{ $sale->discount_percent}}</td></tr>
                                                            <tr><td><strong style="color:black;">Discount Amount:</strong></td><td style="color:black;">₹{{$sale->total_with_gst - $sale->total_with_discount }}</td></tr>
                                                            <tr><td><strong style="color:black;">Parking Amount:</strong></td><td style="color:black;">₹{{ $sale->parking_amount }}</td></tr>
                                                            <tr>
                                                                <td><strong style="color:black;">No of Installments:</strong></td>
                                                                <td id="remaining-installments-{{ $sale->id }}" style="color:black;">{{ $sale->installments }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong style="color:black;">Installment Date:</strong></td>
                                                                <td id="remaining-installments-{{ $sale->id }}" style="color:black;">{{ $sale->installment_date }}</td>
                                                            </tr>
                                                            <tr><td><strong style="color:black;">Amount per Installment:</strong></td>
                                                            <td style="color:black;">₹{{ $sale->installments > 0 ? number_format($sale->remaining_balance / $sale->installments, 2) : 'N/A' }}</td></tr>
                                                            <tr style="display: none;">
                                                                <td><strong style="color:black;">Remaining Installments Total:</strong></td>
                                                                <td id="remaining-installments-total">{{ count($sales) }}</td>
                                                            </tr>
                                                            <tr><td><strong style="color:black;">Remaining Balance:</strong></td><td style="color:black;">₹{{ $sale->remaining_balance}}</td></tr>
                                                            <tr>
                                                                <td><strong style="color:black;">Remaining Balance after paid Installments:</strong></td>
                                                                <td style="color:black;">₹{{ $remainingBalanceAfterInstallments }}</td>
                                                            </tr>
                                                            
                                                        @elseif ($sale->room->room_type === 'Table space')

                                                                
                                                                <tr><td><strong style="color:black;">Room Number:</strong></td><td style="color:black; text-transform: capitalize">{{ $sale->room->room_number }}</td></tr>
                                                                <tr><td><strong style=" color:black;">Table Type:</strong></td><td style="color:black; ">{{ $sale->room->space_type }}</td></tr>
                                                                @if ($sale->area_calculation_type === 'carpet_area_rate')
                                                                    <tr><td><strong style="color:black;">Space Area sq ft:</strong></td><td style="color:black;">{{ $sale->room->space_area }} sq ft</td></tr>
                                                                    <tr><td><strong style="color:black;">Space Area Price per sq ft:</strong></td><td style="color:black;">₹{{ $sale->room->space_area }}</td></tr>
                                                                    <tr><td><strong style="color:black;">Expected Amount:</strong></td><td style="color:black;">₹{{ $sale->room->space_expected_price }}</td></tr>
                                                                @elseif ($sale->area_calculation_type === 'built_up_area_rate')
                                                                <tr><td><strong style="color:black;">Space Area sq ft:</strong></td><td style="color:black;">{{ $sale->room->space_area }} sq ft</td></tr>
                                                                <tr><td><strong style="color:black;">Space Area Price per sq ft:</strong></td><td style="color:black;">₹{{ $sale->room->space_area }}</td></tr>
                                                                <tr><td><strong style="color:black;">Expected Amount:</strong></td><td style="color:black;">₹{{ $sale->room->space_expected_price }}</td></tr>
                                                               @endif
                                                                <tr><td><strong style="color:black;">Sale Amount:</strong></td><td style="color:black;">₹{{ $sale->sale_amount }}</td></tr>
                                                                <tr><td><strong style="color:black;">Total Amount:</strong></td><td style="color:black;">₹{{ $sale->total_amount }}</td></tr>
                                                                
                                                                <tr><td><strong style="color:black;">GST %:</strong></td><td style="color:black;">{{ $sale->gst_percent }}%</td></tr>
                                                                <tr><td><strong style="color:black;">GST Amount:</strong>
                                                                    <td style="color:black;">₹{{ number_format($sale->total_with_gst - $sale->room_rate, 2) }}</td></tr>
                                                                <tr><td><strong style="color:black;">Total With GST:</strong></td><td style="color:black;">₹{{ $sale->total_with_gst }}</td></tr>

                                                                <tr><td><strong style="color:black;">Discount %:</strong></td><td style="color:black;">₹{{ $sale->discount_percent}}</td></tr>
                                                                <tr><td><strong style="color:black;">Discount Amount:</strong></td><td style="color:black;">₹{{$sale->total_with_gst - $sale->total_with_discount }}</td></tr>
                                                                <tr><td><strong style="color:black;">Parking Amount:</strong></td><td style="color:black;">₹{{ $sale->parking_amount }}</td></tr>
                                                                <tr>
                                                                    <td><strong style="color:black;">No of Installments:</strong></td>
                                                                    <td id="remaining-installments-{{ $sale->id }}" style="color:black;">{{ $sale->installments }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong style="color:black;">Installment Date:</strong></td>
                                                                    <td id="remaining-installments-{{ $sale->id }}" style="color:black;">{{ $sale->installment_date }}</td>
                                                                </tr>
                                                                <tr><td><strong style="color:black;">Amount per Installment:</strong></td>
                                                                    <td style="color:black;">₹{{ $sale->installments > 0 ? number_format($sale->remaining_balance / $sale->installments, 2) : 'N/A' }}</td></tr>
                                                                    <tr style="display: none;">
                                                                        <td><strong style="color:black;">Remaining Installments Total:</strong></td>
                                                                        <td id="remaining-installments-total">{{ count($sales) }}</td>
                                                                    </tr>
                                                                    <tr><td><strong style="color:black;">Remaining Balance:</strong></td><td style="color:black;">₹{{ $sale->remaining_balance}}</td></tr>
                                                                    <tr>
                                                                        <td><strong style="color:black;">Remaining Balance after paid Installments:</strong></td>
                                                                        <td style="color:black;">₹{{ $remainingBalanceAfterInstallments }}</td>
                                                                    </tr>                                          
                                                                                                            
                                                                    
                                                        @elseif ($sale->room->room_type === 'Chair space')


                                                        <tr><td><strong style="color:black;">Room Number:</strong></td><td style="color:black; text-transform: capitalize">{{ $sale->room->room_number }}</td></tr>
                                                        <tr><td><strong style=" color:black;">Chair Type:</strong></td><td style="color:black; ">{{ $sale->room->Chair_type }}</td></tr>
                                                        <tr><td><strong style=" color:black;">Chair Name:</strong></td><td style="color:black; ">{{ $sale->room->Chair_name }}</td></tr>
                                                        @if ($sale->area_calculation_type === 'carpet_area_rate')
                                                            <tr><td><strong style="color:black;">Space Area sq ft:</strong></td><td style="color:black;">{{ $sale->room->chair_space_in_sq }} sq ft</td></tr>
                                                            <tr><td><strong style="color:black;">Space Area Price per sq ft:</strong></td><td style="color:black;">₹{{ $sale->room->chair_space_rate }}</td></tr>
                                                            <tr><td><strong style="color:black;">Expected Amount:</strong></td><td style="color:black;">₹{{ $sale->room->chair_space_expected_price }}</td></tr>
                                                        @elseif ($sale->area_calculation_type === 'built_up_area_rate')
                                                        <tr><td><strong style="color:black;">Space Area sq ft:</strong></td><td style="color:black;">{{ $sale->room->chair_space_in_sq }} sq ft</td></tr>
                                                        <tr><td><strong style="color:black;">Space Area Price per sq ft:</strong></td><td style="color:black;">₹{{ $sale->room->chair_space_rate }}</td></tr>
                                                        <tr><td><strong style="color:black;">Expected Amount:</strong></td><td style="color:black;">₹{{ $sale->room->chair_space_expected_price }}</td></tr>
                                                       @endif
                                                        <tr><td><strong style="color:black;">Sale Amount:</strong></td><td style="color:black;">₹{{ $sale->sale_amount }}</td></tr>
                                                        <tr><td><strong style="color:black;">Total Amount:</strong></td><td style="color:black;">₹{{ $sale->total_amount }}</td></tr>
                                                        
                                                        <tr><td><strong style="color:black;">GST %:</strong></td><td style="color:black;">{{ $sale->gst_percent }}%</td></tr>
                                                        <tr><td><strong style="color:black;">GST Amount:</strong>
                                                            <td style="color:black;">₹{{ number_format($sale->total_with_gst - $sale->room_rate, 2) }}</td></tr>
                                                        <tr><td><strong style="color:black;">Total With GST:</strong></td><td style="color:black;">₹{{ $sale->total_with_gst }}</td></tr>

                                                        <tr><td><strong style="color:black;">Discount %:</strong></td><td style="color:black;">₹{{ $sale->discount_percent}}</td></tr>
                                                        <tr><td><strong style="color:black;">Discount Amount:</strong></td><td style="color:black;">₹{{$sale->total_with_gst - $sale->total_with_discount }}</td></tr>
                                                        <tr><td><strong style="color:black;">Parking Amount:</strong></td><td style="color:black;">₹{{ $sale->parking_amount }}</td></tr>
                                                        <tr>
                                                            <td><strong style="color:black;">No of Installments:</strong></td>
                                                            <td id="remaining-installments-{{ $sale->id }}" style="color:black;">{{ $sale->installments }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong style="color:black;">Installment Date:</strong></td>
                                                            <td id="remaining-installments-{{ $sale->id }}" style="color:black;">{{ $sale->installment_date }}</td>
                                                        </tr>
                                                        <tr><td><strong style="color:black;">Amount per Installment:</strong></td>
                                                        <td style="color:black;">₹{{ $sale->installments > 0 ? number_format($sale->remaining_balance / $sale->installments, 2) : 'N/A' }}</td></tr>
                                                        <tr style="display: none;">
                                                            <td><strong style="color:black;">Remaining Installments Total:</strong></td>
                                                            <td id="remaining-installments-total">{{ count($sales) }}</td>
                                                        </tr>
                                                        <tr><td><strong style="color:black;">Remaining Balance:</strong></td><td style="color:black;">₹{{ $sale->remaining_balance}}</td></tr>
                                                        <tr>
                                                            <td><strong style="color:black;">Remaining Balance after paid Installments:</strong></td>
                                                            <td style="color:black;">₹{{ $remainingBalanceAfterInstallments }}</td>
                                                        </tr>     
                                                        
                                                        @elseif ($sale->room->room_type === 'Kiosk')

                                                                
                                                                <tr><td><strong style="color:black;">Room Number:</strong></td><td style="color:black; text-transform: capitalize">{{ $sale->room->room_number }}</td></tr>
                                                                <tr><td><strong style=" color:black;">kiosk Name:</strong></td><td style="color:black; ">{{ $sale->room->kiosk_name }}</td></tr>
                                                                <tr><td><strong style=" color:black;">kiosk Type:</strong></td><td style="color:black; ">{{ $sale->room->kiosk_type }}</td></tr>
                                                                @if ($sale->area_calculation_type === 'carpet_area_rate')
                                                                    <tr><td><strong style="color:black;">kiosk Area sq ft:</strong></td><td style="color:black;">{{ $sale->room->kiosk_area }} sq ft</td></tr>
                                                                    <tr><td><strong style="color:black;">kiosk Area Price per sq ft:</strong></td><td style="color:black;">₹{{ $sale->room->kiosk_rate }}</td></tr>
                                                                    <tr><td><strong style="color:black;">Expected Amount:</strong></td><td style="color:black;">₹{{ $sale->room->kiosk_expected_price }}</td></tr>
                                                                @elseif ($sale->area_calculation_type === 'built_up_area_rate')
                                                                    <tr><td><strong style="color:black;">kiosk Area sq ft:</strong></td><td style="color:black;">{{ $sale->room->kiosk_area }} sq ft</td></tr>
                                                                    <tr><td><strong style="color:black;">kiosk Area Price per sq ft:</strong></td><td style="color:black;">₹{{ $sale->room->kiosk_rate }}</td></tr>
                                                                    <tr><td><strong style="color:black;">Expected Amount:</strong></td><td style="color:black;">₹{{ $sale->room->kiosk_expected_price }}</td></tr>
                                                                @endif
                                                                <tr><td><strong style="color:black;">Sale Amount:</strong></td><td style="color:black;">₹{{ $sale->sale_amount }}</td></tr>
                                                                <tr><td><strong style="color:black;">Total Amount:</strong></td><td style="color:black;">₹{{ $sale->total_amount }}</td></tr>
                                                                
                                                                <tr><td><strong style="color:black;">GST %:</strong></td><td style="color:black;">{{ $sale->gst_percent }}%</td></tr>
                                                                <tr><td><strong style="color:black;">GST Amount:</strong>
                                                                    <td style="color:black;">₹{{ number_format($sale->total_with_gst - $sale->room_rate, 2) }}</td></tr>
                                                                <tr><td><strong style="color:black;">Total With GST:</strong></td><td style="color:black;">₹{{ $sale->total_with_gst }}</td></tr>

                                                                <tr><td><strong style="color:black;">Discount %:</strong></td><td style="color:black;">₹{{ $sale->discount_percent}}</td></tr>
                                                                <tr><td><strong style="color:black;">Discount Amount:</strong></td><td style="color:black;">₹{{$sale->total_with_gst - $sale->total_with_discount }}</td></tr>
                                                                <tr><td><strong style="color:black;">Parking Amount:</strong></td><td style="color:black;">₹{{ $sale->parking_amount }}</td></tr>
                                                                <tr>
                                                                    <td><strong style="color:black;">No of Installments:</strong></td>
                                                                    <td id="remaining-installments-{{ $sale->id }}" style="color:black;">{{ $sale->installments }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong style="color:black;">Installment Date:</strong></td>
                                                                    <td id="remaining-installments-{{ $sale->id }}" style="color:black;">{{ $sale->installment_date }}</td>
                                                                </tr>
                                                                <tr><td><strong style="color:black;">Amount per Installment:</strong></td>
                                                                <td style="color:black;">₹{{ $sale->installments > 0 ? number_format($sale->remaining_balance / $sale->installments, 2) : 'N/A' }}</td></tr>
                                                                <tr style="display: none;">
                                                                    <td><strong style="color:black;">Remaining Installments Total:</strong></td>
                                                                    <td id="remaining-installments-total">{{ count($sales) }}</td>
                                                                </tr>
                                                                <tr><td><strong style="color:black;">Remaining Balance:</strong></td><td style="color:black;">₹{{ $sale->remaining_balance}}</td></tr>
                                                                <tr>
                                                                    <td><strong style="color:black;">Remaining Balance after paid Installments:</strong></td>
                                                                    <td style="color:black;">₹{{ $remainingBalanceAfterInstallments }}</td>
                                                                </tr>                 


                                                                        @endif
                                                                    </tbody>
                                                                </table>
                                                                <tbody>
                                                                    @foreach($sales as $sale)
                                                                       
                                                                        <tr>
                                                                            <td colspan="4">
                                                                                <div class="card mt-3">
                                                                                    <div class="card-header">
                                                                                        <strong style="text-transform: capitalize">{{ $sale->room->room_type }} Details</strong>
                                                                                    </div>
                                                                                    <div class="card-body">
                                                                                        <div class="row mb-4">
                                                                                            <div class="col-12">
                                                                                                <h4>Loan Details</h4>
                                                                                                <table class="table table-sm table-bordered">
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <th>Loan No</th>
                                                                                                            <td>{{ $customer->id }}</td>
                                                                                                        </tr>
                                                                                                        <tr>
                                                                                                            <th>Disb Date</th>
                                                                                                            <td>{{ $customer->created_at->format('d/m/Y') }}</td>
                                                                                                        </tr>
                                                                                                        <tr>
                                                                                                            <th>Cost of Asset</th>
                                                                                                            <td>{{ $customer->total_with_discount }}</td>
                                                                                                        </tr>
                                                                                                        <tr>
                                                                                                            <th>EMI Start Date</th>
                                                                                                            <td>{{ $emi_start_date->format('d/m/Y') }}</td>
                                                                                                        </tr>
                                                                                                        <tr>
                                                                                                            <th>EMI End Date</th>
                                                                                                            <td>{{ $emi_end_date->format('d/m/Y') }}</td>
                                                                                                        </tr>
                                                                                                        <tr>
                                                                                                            <th>EMI Amount</th>
                                                                                                            <td>{{ $emi_amount }}</td>
                                                                                                        </tr>
                                                                                                        <tr>
                                                                                                            <th>Tenure (Months)</th>
                                                                                                            <td>{{ $tenure_months }}</td>
                                                                                                        </tr>
                                                                                                        <tr>
                                                                                                            <th>Asset</th>
                                                                                                            <td>{{ $room->room_type }}</td>
                                                                                                        </tr>
                                                                                                        <tr>
                                                                                                            <th>Loan Amount</th>
                                                                                                            <td>{{ $customer->remaining_balance }}</td>
                                                                                                        </tr>
                                                                                                        <tr>
                                                                                                            <th>Current EMI OS</th>
                                                                                                            <td>{{ $remainingBalanceAfterInstallments }}</td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </div>
                                                                                        </div>
                                                                                    
                                                                                        <h5 class="mt-4">Installment Details</h5>
                                                                                        <form id="markAsPaidForm" method="POST" action="{{ route('admin.installments.markMultipleAsPaid') }}">
                                                                                            @csrf
                                                                                            @method('PUT')
                                                                                            <table class="table table-sm table-bordered">
                                                                                                <thead>
                                                                                                    <tr>
                                                                                                        <th>SL No</th>
                                                                                                        <th>Select</th>
                                                                                                        <th>ID</th>
                                                                                                        <th>Installment Date</th>
                                                                                                        <th>Amount</th>
                                                                                                        <th>Transaction Details</th>
                                                                                                        <th>Bank Details</th>
                                                                                                        <th>Status</th>
                                                                                                    </tr>
                                                                                                </thead>
                                                                                                <tbody>
                                                                                                    @foreach($installments as $installment)
                                                                                                        <tr>
                                                                                                            <td>{{ $loop->iteration }}</td>
                                                                                                            <td>
                                                                                                                <input type="checkbox" name="installments[]" value="{{ $installment->id }}"
                                                                                                                    @if($installment->status === 'paid') style="display: none;" @endif>
                                                                                                            </td>
                                                                                                            <td>{{ $installment->id }}</td>
                                                                                                            <td>
                                                                                                                @if($installment->status === 'paid')
                                                                                                                    {{ $installment->installment_date->format('d/m/Y') }}
                                                                                                                @else
                                                                                                                    <input type="date" name="installment_dates[{{ $installment->id }}]" value="{{ $installment->installment_date->format('Y-m-d') }}">
                                                                                                                @endif
                                                                                                            </td>
                                                                                                            <td>{{ $installment->installment_amount }}</td>
                                                                                                            <td>
                                                                                                                @if($installment->status === 'paid')
                                                                                                                    <span style="color: green; font-weight: bold;">{{ $installment->transaction_details }}</span>
                                                                                                                @else
                                                                                                                    <input type="text" name="transaction_details[{{ $installment->id }}]" value="{{ $installment->transaction_details }}">
                                                                                                                @endif
                                                                                                            </td>
                                                                                                            <td>
                                                                                                                @if($installment->status === 'paid')
                                                                                                                    <span style="color: green; font-weight: bold;">{{ $installment->bank_details }}</span>
                                                                                                                @else
                                                                                                                    <input type="text" name="bank_details[{{ $installment->id }}]" value="{{ $installment->bank_details }}">
                                                                                                                @endif
                                                                                                            </td>
                                                                                                            <td>
                                                                                                                @if($installment->status === 'paid')
                                                                                                                    <span class="badge bg-success">Paid</span>
                                                                                                                @else
                                                                                                                    <span class="badge bg-danger">Pending</span>
                                                                                                                @endif
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    @endforeach
                                                                                                </tbody>
                                                                                            </table>
                                                                                            <button type="submit" class="btn btn-primary">Mark Selected as Paid</button>
                                                                                        </form>
                                                                                        
                                                                                        
                                                                                        <a href="{{ route('admin.customers.download', ['customerName' => $customer->customer_name]) }}" class="btn btn-secondary mt-3">Download Details as CSV</a>
                                                                                    </div>
                                                                                    
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                                        </tbody>
                                                            </table>
                                                                    </div>
                                                                @endforeach
                                                                                        <!-- Mark Paid Modal -->
                                                                                        <div class="modal fade" id="markPaidModal" tabindex="-1" role="dialog" aria-labelledby="markPaidModalLabel" aria-hidden="true">
                                                                                            <div class="modal-dialog" role="document">
                                                                                                <div class="modal-content">
                                                                                                    <div class="modal-header">
                                                                                                        <h5 class="modal-title" id="markPaidModalLabel">Mark Installment as Paid</h5>
                                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                            <span aria-hidden="true">&times;</span>
                                                                                                        </button>
                                                                                                    </div>
                                                                                                    <form id="markPaidForm" method="POST">
                                                                                                        @csrf
                                                                                                        @method('PUT')
                                                                                                        <input type="hidden" name="installment_id" id="modalInstallmentId">
                                                                                                        <div class="modal-body">
                                                                                                            <div class="form-group">
                                                                                                                <label for="modalInstallmentDate">Installment Date</label>
                                                                                                                <input type="text" class="form-control" id="modalInstallmentDate" name="installment_date">
                                                                                                            </div>
                                                                                                            <div class="form-group">
                                                                                                                <label for="modalInstallmentAmount">Installment Amount</label>
                                                                                                                <input type="text" class="form-control" id="modalInstallmentAmount" name="installment_amount" readonly>
                                                                                                            </div>
                                                                                                            <div class="form-group">
                                                                                                                <label for="transaction_details">Transaction Details</label>
                                                                                                                <input type="text" class="form-control" id="modalTransactionDetails" name="transaction_details">
                                                                                                            </div>
                                                                                                            <div class="form-group">
                                                                                                                <label for="bank_details">Bank Details</label>
                                                                                                                <input type="text" class="form-control" id="modalBankDetails" name="bank_details">
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="modal-footer">
                                                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                                                            <button type="submit" class="btn btn-primary">Mark as Paid</button>
                                                                                                        </div>
                                                                                                    </form>
                                                                                                    
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>


<script>
 document.addEventListener('DOMContentLoaded', function () {
    var markPaidModal = document.getElementById('markPaidModal');
    var markPaidForm = document.getElementById('markPaidForm');
    var modalInstallmentId = document.getElementById('modalInstallmentId');
    var modalInstallmentDate = document.getElementById('modalInstallmentDate');
    var modalInstallmentAmount = document.getElementById('modalInstallmentAmount');
    var modalTransactionDetails = document.getElementById('modalTransactionDetails');
    var modalBankDetails = document.getElementById('modalBankDetails');

    markPaidModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var customerId = button.getAttribute('data-customer-id');
        var installmentId = button.getAttribute('data-installment-id');
        var installmentDate = button.getAttribute('data-installment-date');
        var installmentAmount = button.getAttribute('data-installment-amount');
        var transactionDetails = button.getAttribute('data-transaction-details');
        var bankDetails = button.getAttribute('data-bank-details');

        // Set form action URL
        markPaidForm.action = '/admin/customers/' + customerId + '/installments/' + installmentId + '/markAsPaid';

        // Set hidden input values
        modalInstallmentId.value = installmentId;
        modalInstallmentDate.value = installmentDate;
        modalInstallmentAmount.value = installmentAmount;
        modalTransactionDetails.value = transactionDetails;
        modalBankDetails.value = bankDetails;
    });
});


</script>
@endsection