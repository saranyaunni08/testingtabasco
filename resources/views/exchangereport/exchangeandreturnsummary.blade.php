@extends('layouts.default')

@section('content')
<div class="container">
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            text-align: center;
            padding: 8px;
        }

        th {
            background-color: #00838F;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .section-header {
            text-align: center;
            font-weight: bold;
            font-size: 18px;
            margin: 20px 0;
            background-color: #00838F;
            color: white;
            padding: 10px 0;
        }

        .total-row {
            font-weight: bold;
        }

        .negative-amount {
            color: red;
        }

        .note {
            color: red;
            font-size: 12px;
        }
    </style>
    <div style="text-align: right;">
        <a href="{{ route('admin.exchange_return_summary.pdf', $building->id) }}" class="btn btn-primary">
            <i class="fas fa-arrow-down"></i> Download PDF
        </a>

    </div>

    <!-- RETURN SUMMARY SECTION -->
    <div class="section-header">RETURN SUMMARY</div>
    <table>
        <thead>
            <tr>
                <th>CLIENT NAME</th>
                <th>FLOOR</th>
                <th>NO</th>
                <th>TYPE</th>
                <th>TOTAL SALE AMOUNT</th>
            </tr>
        </thead>
        <tbody>
            @php
                $grandTotal = 0;
                // Sort the saleReturns by room floor in ascending order
                $sortedSaleReturns = $saleReturns->sortBy(function ($saleReturn) {
                    return $saleReturn->sale->room->room_floor ?? 0; // Sort by room_floor
                });
            @endphp
            @foreach($sortedSaleReturns as $saleReturn)
                        @php
                            $sale = $saleReturn->sale;
                            $room = $sale ? $sale->room : null;
                            $totalSaleAmount = $saleReturn->total_sale_amount ?? 0;
                            $grandTotal += $totalSaleAmount;
                        @endphp
                        <tr>
                            <td>{{ $sale->customer_name ?? 'N/A' }}</td>
                            <td>{{ $room->room_floor ?? 'N/A' }}</td>
                            <td>{{ $room->room_number ?? 'N/A' }}</td>
                            <td>{{ $room->room_type ?? 'N/A' }}</td>
                            <td>{{ number_format($totalSaleAmount, 2) }}</td>
                        </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="4">Total</td>
                <td>{{ number_format($grandTotal, 2) }}</td>
            </tr>
        </tbody>
    </table>


    <!-- EXCHANGE SUMMARY SECTION -->
    <div class="section-header">EXCHANGE SUMMARY</div>
    <table>
        <thead>
            <tr>
                <th>CLIENT NAME</th>
                <th>FLOOR</th>
                <th>NO</th>
                <th>TYPE</th>
                <th>TOTAL SALE AMOUNT</th>
            </tr>
        </thead>
        <tbody>
            @php 
                            $totalSaleAmount = 0;
                $totalExchangedAmount = 0;

                // Sort exchangeSales by exchange_room_floor in ascending order
                $sortedExchangeSales = $exchangeSales->sortBy(function ($sale) {
                    return $sale->exchangedSale->exchange_room_floor ?? 0; // Sort by exchange_room_floor
                });
            @endphp
            @foreach($sortedExchangeSales as $sale)
                        @php
                            $exchangedSale = $sale->exchangedSale;
                            $exchangeAmount = $exchangedSale ? $exchangedSale->exchange_build_up_area * $exchangedSale->exchange_sale_amount : 0;
                            $totalSaleAmount += $sale->build_up_area * $sale->sale_amount;
                            $totalExchangedAmount += $exchangeAmount;
                        @endphp
                        <tr>
                            <td>{{ $exchangedSale->exchange_customer_name ?? 'No Exchange' }}</td>
                            <td>{{ $exchangedSale->exchange_room_floor ?? 'N/A' }}</td>
                            <td>{{ $exchangedSale->exchange_room_number ?? 'N/A' }}</td>
                            <td>{{ $exchangedSale->exchange_room_type ?? 'N/A' }}</td>
                            <td>{{ number_format($exchangeAmount, 2) }}</td>
                        </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="4">Total</td>
                <td>{{ number_format($totalExchangedAmount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- PAYABLE / RECEIVABLE SECTION -->
    @php
        $payableReceivable = $grandTotal - $totalExchangedAmount;
    @endphp
    <p style="text-align: center; font-weight: bold; margin-top: 20px;">
        Payable/Receivable:
        <span class="{{ $payableReceivable < 0 ? 'negative-amount' : 'positive-amount' }}">
            ({{ number_format($payableReceivable, 2) }})
        </span>
    </p>



</div>
@endsection