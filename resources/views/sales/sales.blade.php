@extends('layouts.default', ['title' => 'Sales Information'])

@section('content')
<div class="container-fluid py-4">
    @php
        // Group sales by building
        $salesByBuilding = $sales->groupBy(function($sale) {
            return $sale->room->building->building_name; // Assuming each room belongs to a building and building has a name
        });
    @endphp

    @foreach ($salesByBuilding as $buildingName => $buildingSales)
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-info shadow-info border-radius-lg p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="card-title text-white mb-0">{{ $buildingName }} - Sales Information</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-2">
                        <div class="row p-4">
                            <div class="table-responsive mb-4">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>Sl. No</th>
                                            <th>Room Number</th>
                                            <th>Sq Ft</th>
                                            <th>Rate</th>
                                            <th>Expected Amount</th>
                                            <th>Sale Amount</th>
                                            <th>Parking Amount</th>
                                            <th>Total Amount</th>
                                            <th>Customer Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($buildingSales as $index => $sale)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $sale->room->room_number }}</td>
                                                <td>
                                                    @if ($sale->room->room_type === 'Flat')
                                                        {{ $sale->room->total_sq_ft }}
                                                    @elseif ($sale->room->room_type === 'Shops')
                                                        {{ $sale->room->shop_area }}
                                                    @elseif ($sale->room->room_type === 'Car parking')
                                                        {{ $sale->room->parking_area }}
                                                    @elseif ($sale->room->room_type === 'Table space')
                                                        {{ $sale->room->space_area }}
                                                    @elseif ($sale->room->room_type === 'Kiosk')
                                                        {{ $sale->room->kiosk_area }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($sale->room->room_type === 'Flat')
                                                        {{ $sale->room->total_sq_rate }}
                                                    @elseif ($sale->room->room_type === 'Shops')
                                                        {{ $sale->room->shop_rate }}
                                                    @elseif ($sale->room->room_type === 'Car parking')
                                                        {{ $sale->room->parking_rate }}
                                                    @elseif ($sale->room->room_type === 'Table space')
                                                        {{ $sale->room->space_rate }}
                                                    @elseif ($sale->room->room_type === 'Kiosk')
                                                        {{ $sale->room->kiosk_rate }}
                                                    @endif
                                                </td>
                                                <td>{{ $sale->room->expected_amount }}</td>
                                                <td>{{ $sale->sale_amount }}</td>
                                                <td>{{ $sale->parking_amount }}</td>
                                                <td>{{ $sale->total_amount }}</td>
                                                <td>{{ $sale->customer_name }}</td>
                                                <td>
                                                    <!-- Soft Delete Button -->
                                                    <form action="{{ route('admin.sales.soft-delete', $sale->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-warning btn-sm">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
