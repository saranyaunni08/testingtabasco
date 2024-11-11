@extends('layouts.default')

@section('title', 'Commercial')

@section('content')
    <div class="container my-4">
        <h3 class="text-primary">Commercial - Shops Sales Report</h3>

        @foreach($groupedSalesByFloor as $floor => $sales)
            <div class="my-4">
                <h4>Floor {{ $floor }}</h4>
                <table class="table table-bordered table-striped mb-4">
                    <thead class="table-dark">
                        <tr>
                            <th>Door No</th>
                            <th>Type</th>
                            <th>SQFT</th>
                            <th>Sales Price</th>
                            <th>Total Sale Amount after Discount</th>
                            <th>Client Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $sale)
                            <tr>
                                <td>{{ $sale->room->room_number ?? 'N/A' }}</td>
                                <td>{{ $sale->room->room_type ?? 'N/A' }}</td>
                                <td>
                                    @if($sale->room)
                                        @if($sale->area_calculation_type === 'super_build_up_area')
                                            {{ $sale->room->build_up_area }}
                                        @elseif($sale->area_calculation_type === 'carpet_area')
                                            {{ $sale->room->carpet_area }}
                                        @else
                                            N/A
                                        @endif
                                    @endif
                                </td>
                                <td>{{ number_format($sale->sale_amount, 2) }}</td>
                                <td>{{ number_format($sale->final_amount, 2) }}</td>
                                <td>{{ $sale->customer_name ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                        <tr class="table-secondary font-weight-bold">
                            <td></td>
                            <td>Total</td>
                            <td>{{ $sales->sum(function($sale) {
                                return $sale->area_calculation_type === 'super_build_up_area'
                                    ? $sale->build_up_area
                                    : ($sale->area_calculation_type === 'carpet_area'
                                        ? $sale->carpet_area
                                        : 0);
                            }) }}</td>
                            <td></td>
                            <td>{{ number_format($sales->sum('final_amount'), 2) }}</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endforeach

      @endsection
