<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commercial Breakup PDF</title>
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
    <div class="container">
        <h2 style="text-align: center;">Commercial Detailed Breakup</h2>
        <table>
            <thead>
                <tr>
                    <th>FLOOR</th>
                    <th>TYPE</th>
                    <th>SHOP NO</th>
                    <th>BUILT-UP AREA (In Sq Ft)</th>
                    <th>CARPET AREA (In Sq Ft)</th>
                    <th>EXPECTED / SQ.FT</th>
                    <th>EXPECTED SALE</th>
                    <th>SALE AMOUNT</th>
                    <th>SALE / SQ.FT</th>
                    <th>DIFFERENCE</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalBuildUpArea = $totalCarpetArea = $totalExpectedAmount = $totalSaleAmount = $totalDifference = 0;
                @endphp

                @foreach ($commercialspaces->sortBy('room_floor') as $space)
                    @php
                        $difference = $space->total_amount - $space->expected_amount;
                    @endphp
                    <tr>
                        <td>{{ $space->room_floor }}</td>
                        <td>{{ $space->room_type }}</td>
                        <td>{{ $space->room_number }}</td>
                        <td>{{ $space->build_up_area }}</td>
                        <td>{{ $space->carpet_area }}</td>
                        <td>{{ number_format($space->super_build_up_price, 2) }}</td>
                        <td>{{ number_format($space->expected_amount, 2) }}</td>
                        <td>{{ number_format($space->total_amount, 2) }}</td>
                        <td>{{ number_format($space->sale_amount, 2) }}</td>
                        <td class="{{ $difference < 0 ? 'red' : 'green' }}">₹ {{ number_format($difference, 2) }}</td>
                        <td class="{{ $space->status == 'SOLD' ? 'green' : 'blue' }}">{{ $space->status }}</td>
                    </tr>

                    @php
                        $totalBuildUpArea += $space->build_up_area;
                        $totalCarpetArea += $space->carpet_area;
                        $totalExpectedAmount += $space->expected_amount;
                        $totalSaleAmount += $space->total_amount;
                        $totalDifference += $difference;
                    @endphp
                @endforeach

                <tr class="totals-row">
                    <td colspan="3" class="text-right">TOTAL</td>
                    <td>{{ $totalBuildUpArea }}</td>
                    <td>{{ $totalCarpetArea }}</td>
                    <td></td>
                    <td>{{ number_format($totalExpectedAmount, 2) }}</td>
                    <td>{{ number_format($totalSaleAmount, 2) }}</td>
                    <td></td>
                    <td>{{ number_format($totalDifference, 2) }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
