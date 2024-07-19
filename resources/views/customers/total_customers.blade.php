@extends('layouts.default', ['title' => 'Customer Details'])

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Customer Details</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="customerDetailsTable" class="table table-bordered table-hover table-striped" style="width:100%">
                    <thead class="table-dark">
                        <tr>
                            <th>Sl.No</th>
                            <th>Customer Name</th>
                            <th>Flat/Shop</th>
                            <th>Sale Amount</th>
                            <th>Cash</th>
                            <th>GST</th>
                            <th>Total</th>
                            <th>Balance to Receive</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $index => $sale)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $sale->customer_name }}</td>
                            <td>{{ $sale->room->room_type }}</td>
                            <td>₹{{ $sale->total_amount }}</td>
                            <td>₹{{ $sale->in_hand_amount }}</td>
                            <td>₹{{ number_format($sale->total_with_gst - $sale->total_amount, 2) }}</td>
                            <td>₹{{ $sale->total_with_gst }}</td>
                            <td>₹{{ $sale->remaining_balance }}</td>
                            <td>
                                <button class="btn btn-primary btn-sm view-details-btn" data-toggle="modal" data-target="#customerDetailsModal{{ $sale->id }}">
                                    View Details
                                </button>
                            </td>
                        </tr>

                        <!-- Modal for Customer Details -->
                        <div class="modal fade" id="customerDetailsModal{{ $sale->id }}" tabindex="-1" role="dialog" aria-labelledby="customerDetailsModalLabel{{ $sale->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="customerDetailsModalLabel{{ $sale->id }}">{{ $sale->customer_name }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Building Name:</strong> {{ $sale->room->building->building_name }}</p>
                                        <p><strong>Contact:</strong> {{ $sale->customer_contact }}</p>
                                        <p><strong>Email:</strong> {{ $sale->customer_email }}</p>
                                        <p><strong>Room Type:</strong> {{ $sale->room->room_type }}</p>
                                        
                                        @if ($sale->room->room_type === 'Flat')
                                            <p><strong>Room Number:</strong> {{ $sale->room->room_number }}</p>
                                            <p><strong>Flat Model:</strong> {{ $sale->room->flat_model }}</p>
                                            @if ($sale->area_calculation_type === 'carpet_area_rate')
                                                <p><strong>Carpet Area sq ft:</strong> {{ $sale->room->flat_carpet_area }} sq ft</p>
                                                <p><strong>Carpet Area Price per sq ft:</strong> ₹{{ $sale->room->flat_carpet_area_price }}</p>
                                                <p><strong>Expected Amount:</strong> ₹{{ $sale->room->flat_expected_carpet_area_price }}</p>
                                            @elseif ($sale->area_calculation_type === 'built_up_area_rate')
                                                <p><strong>Super Build Up sq ft:</strong> {{ $sale->room->flat_build_up_area }}</p>
                                                <p><strong>Super Build Up Price per sq ft:</strong> ₹{{ $sale->room->flat_super_build_up_price }}</p>
                                                <p><strong>Expected Amount:</strong> ₹{{ $sale->room->flat_expected_super_buildup_area_price }}</p>
                                            @endif
                                            <p><strong>Sale Amount:</strong> ₹{{ $sale->sale_amount }}</p>
                                            <p><strong>GST Amount:</strong> ₹{{ number_format($sale->total_with_gst - $sale->total_amount, 2) }}</p>
                                            <p><strong>Parking Amount:</strong> ₹{{ $sale->parking_amount }}</p>
                                            <p><strong>No of Installments:</strong> {{ $sale->installments }}</p>
                                            <p><strong>Amount per Installment:</strong> ₹{{ $sale->installments > 0 ? number_format($sale->remaining_balance / $sale->installments, 2) : 'N/A' }}</p>
                                        
                                        
                                            @elseif ($sale->room->room_type === 'Shops')
                                                <p><strong>Room Number:</strong> {{ $sale->room->room_number }}</p>
                                                <p><strong>Flat Model:</strong> {{ $sale->room->flat_model }}</p>
                                                @if ($sale->area_calculation_type === 'carpet_area_rate')
                                                    <p><strong>Carpet Area sq ft:</strong> {{ $sale->room->carpet_area }} sq ft</p>
                                                    <p><strong>Carpet Area Price per sq ft:</strong> ₹{{ $sale->room->carpet_area_price }}</p>
                                                    <p><strong>Expected Amount:</strong> ₹{{ $sale->room->expected_carpet_area_price }}</p>
                                                @elseif ($sale->area_calculation_type === 'built_up_area_rate')
                                                    <p><strong>Super Build Up sq ft:</strong> {{ $sale->room->build_up_area }}</p>
                                                    <p><strong>Super Build Up Price per sq ft:</strong> ₹{{ $sale->room->super_build_up_price }}</p>
                                                    <p><strong>Expected Amount:</strong> ₹{{ $sale->room->expected_super_buildup_area_price }}</p>
                                                @endif
                                                <p><strong>Sale Amount:</strong> ₹{{ $sale->sale_amount }}</p>
                                                <p><strong>GST Amount:</strong> ₹{{ number_format($sale->total_with_gst - $sale->total_amount, 2) }}</p>
                                                <p><strong>Parking Amount:</strong> ₹{{ $sale->parking_amount }}</p>
                                                <p><strong>No of Installments:</strong> {{ $sale->installments }}</p>
                                                <p><strong>Amount per Installment:</strong> ₹{{ $sale->installments > 0 ? number_format($sale->remaining_balance / $sale->installments, 2) : 'N/A' }}</p>                                        @elseif ($sale->room->room_type === 'Table space')

                                                

                                        @elseif ($sale->room->room_type === 'Chair space')
                                                
                                                <p><strong>Room Number:</strong> {{ $sale->room->room_number }}</p>
                                                <p><strong>Flat Model:</strong> {{ $sale->room->flat_model }}</p>
                                                @if ($sale->area_calculation_type === 'carpet_area_rate')
                                                    <p><strong>Carpet Area sq ft:</strong> {{ $sale->room->flat_carpet_area }} sq ft</p>
                                                    <p><strong>Carpet Area Price per sq ft:</strong> ₹{{ $sale->room->flat_carpet_area_price }}</p>
                                                    <p><strong>Expected Amount:</strong> ₹{{ $sale->room->flat_expected_carpet_area_price }}</p>
                                                @elseif ($sale->area_calculation_type === 'built_up_area_rate')
                                                    <p><strong>Super Build Up sq ft:</strong> {{ $sale->room->flat_build_up_area }}</p>
                                                    <p><strong>Super Build Up Price per sq ft:</strong> ₹{{ $sale->room->flat_super_build_up_price }}</p>
                                                    <p><strong>Expected Amount:</strong> ₹{{ $sale->room->flat_expected_super_buildup_area_price }}</p>
                                                @endif
                                                <p><strong>Sale Amount:</strong> ₹{{ $sale->sale_amount }}</p>
                                                <p><strong>GST Amount:</strong> ₹{{ number_format($sale->total_with_gst - $sale->total_amount, 2) }}</p>
                                                <p><strong>Parking Amount:</strong> ₹{{ $sale->parking_amount }}</p>
                                                <p><strong>No of Installments:</strong> {{ $sale->installments }}</p>
                                                <p><strong>Amount per Installment:</strong> ₹{{ $sale->installments > 0 ? number_format($sale->remaining_balance / $sale->installments, 2) : 'N/A' }}</p> 
                                                
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <form action="{{ route('admin.sales.soft-delete', $sale->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Cancel Sale</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal -->
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .view-details-btn {
        padding: 5px 10px;
        font-size: 14px;
    }
</style>
@endpush
