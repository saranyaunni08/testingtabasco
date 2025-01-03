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

   <!-- COMMERCIAL SECTION -->
   <div class="section-header">COMMERCIAL SALES RETURN REPORT</div>
   <table>
    <thead>
        <tr>
            <th>TYPE</th>
            <th>SHOP NO</th>
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
        @forelse($salesReturns->sortBy(fn($salesReturn) => optional($salesReturn->sale->room)->room_floor) as $salesReturn) <!-- Sorting logic added -->
            @if(optional($salesReturn->sale->room)->room_type === 'shops')
                @php
                    $room = $salesReturn->sale->room;
                    $totalSqft += $room->build_up_area ?? 0;
                    $totalSaleAmount += $salesReturn->sale->sale_amount ?? 0;
                @endphp
                <tr>
                    <td>{{ $room->room_type ?? '-' }}</td>
                    <td>{{ $room->room_number ?? '-' }}</td>
                    <td>{{ $room->room_floor ?? '-' }}</td>
                    <td>{{ number_format($room->build_up_area ?? 0) }}</td>
                    <td>{{ number_format($salesReturn->sale->sale_amount ?? 0) }}</td>
                    <td>{{ number_format(($room->build_up_area ?? 0) * ($salesReturn->sale->sale_amount ?? 0), 2) }}</td>
                    <td>{{ $salesReturn->sale->customer_name ?? '-' }}</td>
                    <td>{{ $salesReturn->sale->status ?? '-' }}</td>
                </tr>
            @endif
        @empty
            <tr>
                <td colspan="8">No commercial details available.</td>
            </tr>
        @endforelse
        @if($totalSqft > 0 && $totalSaleAmount > 0)
            <tr class="total-row">
                <td colspan="3">Total</td>
                <td>{{ number_format($totalSqft) }}</td>
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
