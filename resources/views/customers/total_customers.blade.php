<!-- resources/views/admin/customers/total_customers.blade.php -->

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
                        @foreach($customers as $index => $customer)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $customer->customer_name }}</td>
                            <td>{{ $customer->room_type }}</td>
                            <td>₹{{ $customer->sale_amount }}</td>
                            <td>₹{{ $customer->in_hand_amount }}</td>
                            <td>₹{{ number_format($customer->gst_amount, 2) }}</td>
                            <td>₹{{ $customer->total_amount }}</td>
                            <td>₹{{ $customer->remaining_balance }}</td>
                            <td>
                                <button class="btn btn-primary btn-sm view-details-btn" data-toggle="modal" data-target="#customerDetailsModal{{ $customer->id }}">
                                    View Details
                                </button>
                            </td>
                        </tr>

                        <!-- Modal for Customer Details -->
                        <div class="modal fade" id="customerDetailsModal{{ $customer->id }}" tabindex="-1" role="dialog" aria-labelledby="customerDetailsModalLabel{{ $customer->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="customerDetailsModalLabel{{ $customer->id }}">{{ $customer->customer_name }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Building Name:</strong> {{ $customer->room->building->building_name }}</p>
                                        <p><strong>Contact:</strong> {{ $customer->customer_contact }}</p>
                                        <p><strong>Email:</strong> {{ $customer->customer_email }}</p>
                                        <p><strong>Room Type:</strong> {{ $customer->room->room_type }}</p>
                                        <p><strong>Sale Amount:</strong> ₹{{ $customer->sale_amount }}</p>
                                        <p><strong>GST Amount:</strong> ₹{{ number_format($customer->gst_amount, 2) }}</p>
                                        <p><strong>Parking Amount:</strong> ₹{{ $customer->parking_amount }}</p>
                                        <p><strong>No of Installments:</strong> {{ $customer->installments }}</p>
                                        <p><strong>Amount per Installment:</strong> ₹{{ $customer->installments > 0 ? number_format($customer->remaining_balance / $customer->installments, 2) : 'N/A' }}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <form action="{{ route('admin.sales.soft-delete', $customer->id) }}" method="POST">
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
