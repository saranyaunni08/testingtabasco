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
                                                        @elseif ($sale->room->room_type === 'Shops')

                                                            
                                                            <tr><td><strong style="color:black;">Room Number:</strong></td><td style="color:black; text-transform: capitalize">{{ $sale->room->room_number }}</td></tr>
                                                            <tr><td><strong style=" color:black;">Shop Type:</strong></td><td style="color:black; ">{{ $sale->room->flat_model }}</td></tr>
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


                                                        @endif
                                                    </tbody>
                                                </table>
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
                                                                        <h5 class="mt-4">Installment Details</h5>
                                                                        <table class="table table-sm table-bordered">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Installment Date</th>
                                                                                    <th>Installment Amount</th>
                                                                                    <th>Transaction Details</th>
                                                                                    <th>Bank Details</th>
                                                                                    <th>Status</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @forelse($installments as $installment)
                                                                                <tr>
                                                                                    <td>{{ $installment->installment_date }}</td>
                                                                                    <td>{{ $installment->installment_amount }}</td>
                                                                                    <td>{{ $installment->transaction_details }}</td>
                                                                                    <td>{{ $installment->bank_details }}</td>
                                                                                    <td>{{ $installment->status }}</td>
                                                                                    <td>
                                                                                        @if($installment->status == 'pending')
                                                                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#markPaidModal"
                                                                                                data-installment-id="{{ $installment->id }}"
                                                                                                data-installment-date="{{ $installment->installment_date }}"
                                                                                                data-installment-amount="{{ $installment->installment_amount }}">
                                                                                                Mark as Paid
                                                                                            </button>
                                                                                        @endif
                                                                                    </td>
                                                                                </tr>
                                                                            @empty
                                                                                <tr>
                                                                                    <td colspan="6">No installments available</td>
                                                                                </tr>
                                                                            @endforelse
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
            <form id="markAsPaidForm" method="POST" action="{{ route('admin.installments.markAsPaid', $installment->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="installment_id" id="modalInstallmentId">
                    <div class="form-group">
                        <label for="modalInstallmentDate">Installment Date</label>
                        <input type="text" class="form-control" id="modalInstallmentDate" name="installment_date" readonly>
                    </div>
                    <div class="form-group">
                        <label for="modalInstallmentAmount">Installment Amount</label>
                        <input type="text" class="form-control" id="modalInstallmentAmount" name="installment_amount" readonly>
                    </div>
                    <div class="form-group">
                        <label for="transaction_details">Transaction Details</label>
                        <input type="text" class="form-control" id="transaction_details" name="transaction_details">
                    </div>
                    <div class="form-group">
                        <label for="bank_details">Bank Details</label>
                        <input type="text" class="form-control" id="bank_details" name="bank_details">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
            
            
        </div>
    </div>
</div>


                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>


<script>
$('#markPaidModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var installmentId = button.data('installment-id');
    var installmentDate = button.data('installment-date');
    var installmentAmount = button.data('installment-amount');

    var modal = $(this);
    modal.find('#modalInstallmentId').val(installmentId);
    modal.find('#modalInstallmentDate').val(installmentDate);
    modal.find('#modalInstallmentAmount').val(installmentAmount);
});


</script>

@endsection