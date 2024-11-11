@extends('layouts.default')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm p-4">
        <h1 class="text-center text-primary mb-4">Commercial Sales Report</h1>

        <div class="d-flex justify-content-center mb-4 gap-3">
            <a href="{{ route('admin.statements.shop-sales-report') }}" class="btn btn-outline-primary">Commercial</a>
            <a href="{{ route('admin.statements.apartments-sales-report') }}" class="btn btn-outline-secondary">Apartments </a>
            {{-- <a href="{{ route('') }}" class="btn btn-outline-success">All Statements</a> --}}
            <a href="{{ route('admin.statements.commercialsummary') }}" class="btn btn-outline-info">Sales Summary </a>

        </div>
        
        @foreach ($groupedSalesByFloor as $floor => $roomTypes)
        <div class="my-4">
            <h3 class="text-secondary">Floor: {{ $floor }}</h3>
    
            @foreach ($roomTypes as $roomType => $sales)
                <h5 class="text-info mt-3">{{ ucfirst($roomType) }}</h5>
                <table class="table table-bordered table-striped mb-4">
                    <thead class="table-dark">
                        <tr>
                            <th>Shop No</th>
                            <th>SqFt</th>
                            <th>Sales Price</th>
                            <th>Total Sale Amount after Discount</th>
                            <th>Client Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sales as $sale)
                            <tr>
                                <td>{{ $sale->room->room_number ?? 'N/A' }}</td>
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
                                <td>{{ $sale->customer_name }}</td>
                            </tr>
                        @endforeach
    
                        <!-- Subtotal row for each room type -->
                        <tr class="table-secondary font-weight-bold">
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
            @endforeach
        </div>
    @endforeach
    
    <!-- Grand Total Section -->
    <div class="my-4">
        <h3 class="text-primary">Grand Totals</h3>
        <table class="table table-bordered table-striped mb-4">
            <thead class="table-dark">
                <tr>
                    <th>Total SqFt</th>
                    <th>Total Sale Amount after Discount</th>
                  
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ number_format($totalSqft, 2) }}</td>
                    <td>{{ number_format($totalSalesAmount, 2) }}</td>
                    {{-- <td>{{ number_format($totalGST, 2) }}</td> --}}
                    {{-- <td>{{ number_format($totalCashReceived, 2) }}</td> --}}
                    {{-- <td>{{ number_format($totalChequeReceived, 2) }}</td> --}}
                    {{-- <td>{{ number_format($totalReceivable, 2) }}</td> --}}
                    {{-- <td>{{ number_format($balanceReceivable, 2) }}</td> --}}
                </tr>
            </tbody>
        </table>
    </div>
    
    </div>
</div>

<style>
    body {
        background-color: #f8f9fa;
    }

    .container {
        max-width: 100%;
        width: 90%;
    }

    .card {
        border-radius: 10px;
    }

    h1 {
        font-weight: 600;
        color: #007bff;
    }

    table thead th {
        font-size: 16px;
        text-transform: uppercase;
    }

    .text-secondary {
        font-size: 20px;
        font-weight: bold;
    }

    .text-info {
        font-size: 18px;
        font-weight: bold;
        margin-top: 15px;
    }
</style>
@endsection
