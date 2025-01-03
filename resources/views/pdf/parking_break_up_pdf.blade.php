<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking Breakup PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }
        th {
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <h2 class="text-center">Apartment Parking Detailed Breakup</h2>

    <table>
        <thead>
            <tr>
                <th>Floor</th>
                <th>No</th>
                <th>Parking No</th>
                <th>Name</th>
                <th>Expected Sale</th>
                <th>Sale Amount</th>
                <th>Difference</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalExpectedSale = $totalSaleAmount = $totalDifference = 0;
            @endphp
            @foreach ($parkings->sortBy('floor_number') as $parking)
                @php
                    $difference = (($parking->sales->parking_amount_cash ?? 0) + ($parking->sales->parking_amount_cheque ?? 0)) - $parking->amount;
                    $totalExpectedSale += $parking->amount;
                    $totalSaleAmount += ($parking->sales->parking_amount_cash ?? 0) + ($parking->sales->parking_amount_cheque ?? 0);
                    $totalDifference += $difference;
                @endphp
                <tr>
                    <td>{{ $parking->floor_number }}</td>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $parking->slot_number }}</td>
                    <td>{{ $parking->purchaser_name }}</td>
                    <td>{{ number_format($parking->amount, 2) }}</td>
                    <td>{{ number_format(($parking->sales->parking_amount_cash ?? 0) + ($parking->sales->parking_amount_cheque ?? 0), 2) }}</td>
                    <td>{{ number_format($difference, 2) }}</td>
                    <td>{{ $parking->status }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4" class="text-center">Total</td>
                <td>{{ number_format($totalExpectedSale, 2) }}</td>
                <td>{{ number_format($totalSaleAmount, 2) }}</td>
                <td>{{ number_format($totalDifference, 2) }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
