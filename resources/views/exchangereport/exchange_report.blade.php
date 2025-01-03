@extends('layouts.default')

@section('content')
<div class="container">

    <style>
        /* General styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th,
        td {
            border: 1px solid #ddd;
            text-align: center;
            padding: 8px;
        }

        th {
            background-color: #00B8B8;
            color: white;
            text-transform: uppercase;
        }

        .total-row td {
            font-weight: bold;
            background-color: #EDEDED;
        }

        .note {
            color: red;
            font-size: 12px;
            text-align: right;
            margin-top: 10px;
        }

        .header {
            text-align: center;
            background-color: #00B8B8;
            color: white;
            font-weight: bold;
            font-size: 18px;
            padding: 10px 0;
            margin-bottom: 10px;
            width: 110.5%;
        }
    </style>
    <div style="text-align: right;">
    <a href="{{ route('admin.exchange_report.pdf', $building->id) }}" class="btn btn-primary">
    <i class="fas fa-arrow-down"></i> Download PDF
</a>

</div>



    <div class="d-flex justify-content-center mb-4 gap-3">
        <a href="{{ route('admin.exchangereport.exchangeandreturnsummary', $building->id) }}"
            class="btn btn-outline-primary">
            Exchange And Return Summary
        </a>
    </div>

    <div class="header" container-fluid>EXCHANGE REPORT</div>

    <table>
        <thead>
            <tr>
                <th>CUSTOMER NAME</th>
                <th>FLOOR</th>
                <th>SHOP NO</th>
                <th>TYPE</th>
                <th>SQFT</th>
                <th>SALES PRICE</th>
                <th>TOTAL SALE AMOUNT</th>
                <th>FLOOR</th>
                <th>SHOP NO</th>
                <th>TYPE</th>
                <th>SQFT</th>
                <th>SALES PRICE</th>
                <th>TOTAL SALE AMOUNT</th>
                <th>PAYABLE/RECEIVABLE</th>
            </tr>
        </thead>
        <tbody>
            @php
                // Initialize totals
                $totalSqft = 0;
                $totalSaleAmount = 0;
                $totalExchangedSqft = 0;
                $totalExchangedSaleAmount = 0;
                $totalPayableReceivable = 0;
            @endphp

            @foreach($sales as $sale)
                        @php
                            // Accumulate totals
                            $totalSqft += $sale->build_up_area;
                            $totalSaleAmount += $sale->sale_amount * $sale->build_up_area;

                            if ($sale->exchangedSale) {
                                $totalExchangedSqft += $sale->exchangedSale->exchange_build_up_area ?? 0;
                                $totalExchangedSaleAmount += $sale->exchangedSale->exchange_build_up_area * $sale->exchangedSale->exchange_sale_amount ?? 0;

                                $payableReceivable = $sale->sale_amount - $sale->exchangedSale->exchange_sale_amount;
                            } else {
                                $payableReceivable = 0;
                            }
                        @endphp

                        <tr>
                            <!-- Original Sale Data -->
                            <td>{{ $sale->customer_name }}</td>
                            <td>{{ $sale->room_floor }}</td>
                            <td>{{ $sale->room_number }}</td>
                            <td>{{ $sale->room_type }}</td>
                            <td>{{ $sale->build_up_area }}</td>
                            <td>{{ number_format($sale->sale_amount, 2) }}</td>
                            <td>{{ number_format($sale->sale_amount * $sale->build_up_area, 2) }}</td>

                            <!-- Exchanged Sale Data -->
                            <td>{{ $sale->exchangedSale ? $sale->exchangedSale->exchange_room_floor : '' }}</td>
                            <td>{{ $sale->exchangedSale ? $sale->exchangedSale->exchange_room_number : '' }}</td>
                            <td>{{ $sale->exchangedSale ? $sale->exchangedSale->exchange_room_type : '' }}</td>
                            <td>{{ $sale->exchangedSale ? $sale->exchangedSale->exchange_build_up_area : '' }}</td>
                            <td>{{ $sale->exchangedSale && $sale->exchangedSale->exchange_sale_amount ? number_format($sale->exchangedSale->exchange_sale_amount, 2) : '0.00' }}
                            </td>
                            <td>
                                {{ $sale->exchangedSale && $sale->exchangedSale->exchange_build_up_area && $sale->exchangedSale->exchange_sale_amount
                    ? number_format($sale->exchangedSale->exchange_build_up_area * $sale->exchangedSale->exchange_sale_amount, 2)
                    : '0.00' }}
                            </td>

                            <!-- Payable/Receivable Amount -->
                            <td>
                                @php
                                    $payableReceivable = 0;

                                    if ($sale->exchangedSale && $sale->build_up_area && $sale->sale_amount) {
                                        $payableReceivable = ($sale->sale_amount * $sale->build_up_area) -
                                            ($sale->exchangedSale->exchange_sale_amount * $sale->exchangedSale->exchange_build_up_area);
                                    }
                                @endphp
                                {{ number_format($payableReceivable, 2) }}
                            </td>
                        </tr>

                        @php    $totalPayableReceivable += $payableReceivable; @endphp
            @endforeach

        </tbody>

        <!-- Totals Row -->
        <tfoot>
            <tr>
                <th colspan="4">Total</th>
                <th>{{ $totalSqft }}</th>
                <th></th> <!-- Total for Original Total Sale Amount if needed -->
                <th>{{ number_format($totalSaleAmount, 2) }}</th>
                <th></th>
                <th></th>
                <th></th>
                <th>{{ $totalExchangedSqft }}</th>
                <th></th>
                <th>{{ number_format($totalExchangedSaleAmount, 2) }}</th>
                <th>{{ number_format($totalPayableReceivable, 2) }}</th>
            </tr>
        </tfoot>
    </table>


</div>
@endsection