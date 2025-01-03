@extends('layouts.default')

@section('content')
<div class="container">

<style>
        /* General Styles */
        .report-title {
        text-align: center;
        font-size: 20px;
        font-weight: bold;
        background-color: #F89A6B; /* Orange */
        color: white;
        padding: 10px 0;
        margin-bottom: 20px; /* Added space below the title */
    }
        .section-header {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            background-color: #00838F; /* Teal */
            color: white;
            padding: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            text-align: center;
            padding: 8px;
            font-size: 14px;
        }
        th {
            background-color: #00838F; /* Teal Header */
            color: white;
        }
        .total-row {
            font-weight: bold;
        }
        .note {
            color: red;
            font-size: 12px;
            margin-left: 10px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>


<div class="section-header">APARTMENTS SALES RETURN REPORT</div>
<table>
    <thead>
        <tr>
            <th>TYPE</th>
            <th>DOOR NO</th>
            <th>FLOOR</th>
            <th>SQFT</th>
            <th>SALES PRICE</th>
            <th>TOTAL SALE AMOUNT</th>
            <th>CLIENT NAME</th>
            <th>STATUS</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalSqft = 0;
            $totalSaleAmount = 0;
        @endphp
        @forelse ($salesFlats->sortBy(fn($flat) => $flat->sale->room->room_floor ?? 0) as $flat) <!-- Sort by room_floor -->
            @php
                $room = $flat->sale->room ?? null;
                $sqft = $room->flat_build_up_area ?? 0;
                $salePrice = $flat->sale->sale_amount ?? 0;
                $calculatedTotal = $sqft * $salePrice;

                $totalSqft += $sqft;
                $totalSaleAmount += $calculatedTotal;
            @endphp
            <tr>
                <td>{{ $room->room_type ?? 'N/A' }}</td>
                <td>{{ $room->room_number ?? 'N/A' }}</td>
                <td>{{ $room->room_floor ?? 'N/A' }}</td>
                <td>{{ $sqft }}</td>
                <td>{{ number_format($salePrice) }}</td>
                <td>{{ number_format($calculatedTotal) }}</td>
                <td>{{ $flat->sale->customer_name ?? 'N/A' }}</td>
                <td>{{ $flat->sale->status ?? 'N/A' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8">No apartment details available.</td>
            </tr>
        @endforelse
        @if(count($salesFlats) > 0)
            <tr class="total-row">
                <td colspan="3">Total</td>
                <td>{{ $totalSqft }}</td>
                <td></td>
                <td>{{ number_format($totalSaleAmount) }}</td>
                <td></td>
                <td></td>
            </tr>
        @endif
    </tbody>
</table>




</div>
@endsection
