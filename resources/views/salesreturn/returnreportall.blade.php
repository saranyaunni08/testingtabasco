@extends('layouts.default')

@section('content')
<style>
    /* General Styles */
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
    }

    .report-title {
        text-align: center;
        font-size: 20px;
        font-weight: bold;
        background-color: #F89A6B;
        /* Orange */
        color: white;
        padding: 10px 0;
        margin-bottom: 5px;
    }

    .section-header {
        text-align: center;
        font-weight: bold;
        font-size: 16px;
        background-color: #00838F;
        /* Teal */
        color: white;
        padding: 8px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    th,
    td {
        border: 1px solid #ddd;
        text-align: center;
        padding: 8px;
        font-size: 14px;
    }

    th {
        background-color: #00838F;
        /* Teal Header */
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
<div class="container">
    <div class="d-flex justify-content-center mb-4 gap-3">
        <a href="{{ route('admin.salesreturn.commercial', $building->id) }}"
            class="btn btn-outline-primary">Commercial</a>
        <a href="{{ route('admin.salesreturn.apartment', $building->id)}}"
            class="btn btn-outline-secondary">Apartment</a>
        <a href="{{ route('admin.salesreturn.parking', $building->id)}}" class="btn btn-outline-success">Parking</a>



    </div>

    <!-- REPORT TITLE -->
    <div class="report-title">SALES RETURN REPORT</div>

    <!-- COMMERCIAL SECTION -->
    <div class="section-header">COMMERCIAL</div>
@php
    $salesReturns = $salesReturns->sortBy(function($salesReturn) {
        return optional($salesReturn->sale->room)->room_floor;
    });
@endphp

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
        @forelse($salesReturns as $salesReturn)
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


    <!-- APARTMENTS SECTION -->
    <div class="section-header">APARTMENTS</div>
@php
    // Sorting the salesFlats collection by room_floor in ascending order
    $salesFlats = $salesFlats->sortBy(function($flat) {
        return optional($flat->sale->room)->room_floor;
    });
@endphp

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
        @forelse ($salesFlats as $flat)
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



   <!-- PARKING SECTION -->
<div class="section-header">PARKING</div>
@php
    // Sorting the parkingDetails collection by floor_number in ascending order
    $parkingDetails = $parkingDetails->sortBy(function($parking) {
        return optional($parking->sale->parking)->floor_number;
    });
@endphp

<table>
    <thead>
        <tr>
            <th>TYPE</th>
            <th>PARKING NO</th>
            <th>FLOOR</th>
            <th>SALES PRICE</th>
            <th>CLIENT NAME</th>
            <th>STATUS</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalSalesAmount = 0;
        @endphp
        @forelse ($parkingDetails as $parking)
            @php
                $sale = $parking->sale ?? null;
                $parkingData = $sale->parking ?? null;
                $salesAmount = $sale->sale_amount ?? 0;
                $totalSalesAmount += $salesAmount;
            @endphp
            <tr>
                <td>Parking</td>
                <td>{{ $parkingData->slot_number ?? 'N/A' }}</td>
                <td>{{ $parkingData->floor_number ?? 'N/A' }}</td>
                <td>{{ number_format($salesAmount) }}</td>
                <td>{{ $sale->customer_name ?? 'N/A' }}</td>
                <td>{{ $sale->status ?? 'N/A' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6">No parking details available.</td>
            </tr>
        @endforelse
        <tr class="total-row">
            <td colspan="3"><strong>Total</strong></td>
            <td>{{ number_format($totalSalesAmount) }}</td>
            <td colspan="2"></td>
        </tr>
    </tbody>
</table>

@endsection