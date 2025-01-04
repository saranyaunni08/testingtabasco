<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exchange Return Summary Report PDF</title>
    <style>
    /* Styling similar to the original page but without colors */
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
        padding: 10px;
        margin: 10px 0;
        font-size: 18px; /* Reduced font size */
        border: 1px solid #000; /* Border for visual separation */
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin: 0 auto;
        table-layout: fixed; /* Fixed column width */
    }

    th, td {
        border: 1px solid #000; /* Black border */
        text-align: center;
        padding: 5px; /* Reduced padding */
        word-wrap: break-word;
        font-size: 10px; /* Reduced font size */
    }

    th {
        font-weight: bold; /* Emphasize headers */
        text-transform: uppercase;
    }

    .total-row td {
        font-weight: bold;
        border: 1px solid #000; /* Ensure black border */
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
            border: 1px solid #000; /* Border for separation in print */
        }

        .container {
            padding: 5px;
        }
    }
</style>

</head>
<body>
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
</body>
</html>
