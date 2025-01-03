<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apartment Breakup PDF</title>
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
        <h2 style="text-align: center;">Apartment Detailed Breakup</h2>
        <table>
            <thead>
                <tr>
                    <th>FLOOR</th>
                    <th>TYPE</th>
                    <th>DOOR NO</th>
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

                @foreach ($apartments->sortBy('room_floor') as $apartment)
                                @php
                                    $difference = ($apartment->total_amount ?? 0) - ($apartment->expected_amount ?? 0);
                                @endphp
                                <tr>
                                    <td>{{ $apartment->room_floor }}</td>
                                    <td>{{ $apartment->room_type }}</td>
                                    <td>{{ $apartment->room_number }}</td>
                                    <td>{{ $apartment->flat_build_up_area }}</td>
                                    <td>{{ $apartment->flat_carpet_area }}</td>
                                    <td>{{ number_format($apartment->flat_super_build_up_price, 2) }}</td>
                                    <td>{{ number_format($apartment->expected_amount, 2) }}</td>
                                    <td>{{ number_format($apartment->total_amount, 2) }}</td>
                                    <td>{{ number_format($apartment->sale_amount, 2) }}</td>
                                    <td class="{{ $difference < 0 ? 'red' : 'green' }}">{{ number_format($difference, 2) }}</td>
                                    <td class="{{ $apartment->status == 'SOLD' ? 'green' : 'blue' }}">{{ $apartment->status }}</td>
                                </tr>

                                @php
                                    $totalBuildUpArea += $apartment['flat_build_up_area'];
                                    $totalCarpetArea += $apartment['flat_carpet_area'];
                                    $totalExpectedAmount += $apartment['expected_amount'];
                                    $totalSaleAmount += $apartment['sale_amount'];
                                    $totalDifference += $difference;
                                @endphp
                @endforeach

                <tr style="font-weight: bold; background-color: #f8f9fa;">
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