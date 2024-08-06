@extends('layouts.default', ['title' => 'Cancelled Sales', 'page' => 'cancelled-sales'])

@section('content')

<div class="container-fluid py-4">
    <div class="card my-4">
        <div class="card-header bg-danger text-white">
            <h2 class="mb-0">Cancelled Sales</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Room Number</th>
                            <th class="text-center">Shop Model</th>
                            <th class="text-center">Customer Name</th>
                            <th class="text-center">Sale Amount (RS)</th>
                            <th class="text-center">GST Amount</th>
                            <th class="text-center">Parking Amount</th>
                            <th class="text-center">Total Amount</th>
                            <th class="text-center">Cancelled Date</th>
                            <th class="text-center">Actions</th> <!-- New column for actions -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cancelledSales as $index => $sale)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $sale->room->room_number }}</td>
                            <td>{{ $sale->room->shop_type }}</td>
                            <td>{{ $sale->customer_name }}</td>
                            <td class="text-right">{{ $sale->sale_amount }}</td>
                            <td class="text-right">{{ $sale->gst_amount }}</td>
                            <td class="text-right">{{ $sale->parking_amount }}</td>
                            <td class="text-right">{{ $sale->total_amount }}</td>
                            <td class="text-center">{{ $sale->cancelled_at }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.sales.cancelled_details', $sale->id) }}" class="btn btn-primary btn-sm">View</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
