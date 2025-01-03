<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exchange Report PDF</title>
    <style>
    /* Styling similar to the original page */
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .container {
        width: 100%;
        padding: 10px;
    }

    h1 {
        text-align: center;
        background-color: #00B8B8;
        color: white;
        padding: 10px;
        margin: 10px 0;
        font-size: 18px; /* Reduced font size */
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin: 0 auto;
        table-layout: fixed; /* Fixed column width */
    }

    th, td {
        border: 1px solid #ddd;
        text-align: center;
        padding: 5px; /* Reduced padding */
        word-wrap: break-word;
        font-size: 10px; /* Reduced font size */
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

    /* Adjustments for printing */
    @page {
        size: A4;
        margin: 10mm; /* Reduced margins */
    }

    @media print {
        body {
            font-size: 9px; /* Reduced font size */
        }

        table {
            width: 100%;
            font-size: 9px;
        }

        td, th {
            padding: 3px; /* Further reduced padding */
        }

        h1 {
            font-size: 16px;
        }

        .container {
            padding: 5px;
        }
    }
</style>

</head>
<body>
    <div class="container">
        <h1>Exchange Report PDF</h1>
        <table>
            <thead>
                <tr>
                    <th>CUSTOMER NAME</th>
                    <th>FLOOR</th>
                    <th>ROOM TYPE NO</th>
                    <th>TYPE</th>
                    <th>SQFT</th>
                    <th>SALES PRICE</th>
                    <th>TOTAL SALE AMOUNT</th>
                    <th>FLOOR</th>
                    <th>ROOM TYPE NO</th>
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
                    <td>{{ $sale->exchangedSale && $sale->exchangedSale->exchange_sale_amount ? number_format($sale->exchangedSale->exchange_sale_amount, 2) : '0.00' }}</td>
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

                @php
                    $totalPayableReceivable += $payableReceivable;
                @endphp
            @endforeach

        </tbody>

        <!-- Totals Row -->
        <tfoot>
            <tr>
                <th colspan="4">Total</th>
                <th>{{ $totalSqft }}</th>
                <th></th>
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
</body>
</html>
