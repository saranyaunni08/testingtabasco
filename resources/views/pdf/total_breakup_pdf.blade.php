<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Total Build-Up Area Breakdown PDF</title>
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

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        th {
            font-weight: bold;
            text-transform: uppercase;
            /* Optional for a more formal appearance */
        }

        .table-heading {
            text-align: center;
            margin-bottom: 10px;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
            padding: 10px;
        }

        .totals-row {
            font-weight: bold;
            border-top: 2px solid #000;
            /* Distinguish totals with a thicker border */
        }
    </style>


</head>

<body>
    <div class="container">
        <h2 class="table-heading">{{ $building->name }} Total Build-Up Area Breakdown</h2>

        <!-- Apartments Section -->
        <table>
            <thead>
                <tr>
                    <th colspan="11" class="section-title apartment-header">APARTMENT DETAILED BREAKUP</th>
                </tr>
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
                                    <td>
                                        {{ number_format($difference, 2) }}
                                    </td>
                                    <td>
                                        {{ $apartment->status }}
                                    </td>

                                </tr>

                                @php
                                    $totalBuildUpArea += $apartment['flat_build_up_area'];
                                    $totalCarpetArea += $apartment['flat_carpet_area'];
                                    $totalExpectedAmount += $apartment['expected_amount'];
                                    $totalSaleAmount += $apartment['sale_amount'];
                                    $totalDifference += $difference;
                                @endphp
                @endforeach

                <tr class="totals-row">
                    <td colspan="3">TOTAL</td>
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

        <!-- Commercial Spaces Section -->
        <table class="table table-bordered table-striped mb-5">
            <thead>
                <tr>
                    <th colspan="11" class="section-title commercial-header">COMMERCIAL DETAILED BREAKUP</th>
                </tr>
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
                                    // Calculate difference directly within the loop
                                    $difference = $space->total_amount - $space->expected_amount;
                                @endphp

                                <tr>
                                    <td>{{ $space->room_floor }}</td>
                                    <td>{{ $space->room_type }}</td>
                                    <td>{{ $space->room_number }}</td>
                                    <td>{{ $space->build_up_area }}</td>
                                    <td>{{ $space->carpet_area }}</td>
                                    <td>{{ number_format($space->super_build_up_price, 2) }}</td> <!-- Display expected_per_sqft -->
                                    <td>{{ number_format($space->expected_amount, 2) }}</td>
                                    <td>{{ number_format($space->total_amount, 2) }}</td>
                                    <td>{{ number_format($space->sale_amount, 2) }}</td>
                                    <td>{{ number_format($difference, 2) }}</td>
                                    <td>{{ $space->status }}</td>

                                </tr>

                                @php
                                    // Manually accumulate the totals for difference
                                    $totalBuildUpArea += $space->build_up_area;
                                    $totalCarpetArea += $space->carpet_area;
                                    $totalExpectedAmount += $space->expected_amount;
                                    $totalSaleAmount += $space->total_amount;
                                    $totalDifference += $difference;  // Use the calculated $difference
                                @endphp
                @endforeach


                <tr style="font-weight: bold; background-color: #f8f9fa;">
                    <td colspan="3" style="text-align: right;">TOTAL</td>
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
        <!-- Apartment Parking Section -->
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th colspan="8" class="section-title">APARTMENT PARKING DETAILED BREAKUP</th>
        </tr>
        <tr>
            <th>FLOOR</th>
            <th>NO</th>
            <th>PARKING NO</th>
            <th>NAME</th>
            <th>EXPECTED SALE</th>
            <th>SALE AMOUNT</th>
            <th>DIFFERENCE</th>
            <th>STATUS</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalExpectedSale = $totalSaleAmount = $totalDifference = 0;
        @endphp
        @foreach ($parkings->sortBy('floor_number') as $parking) <!-- Sort by floor_number -->
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
                <td>
                    {{ number_format(($parking->sales->parking_amount_cash ?? 0) + ($parking->sales->parking_amount_cheque ?? 0), 2) }}
                </td>
                <td>{{ number_format($difference, 2) }}</td>
                <td>{{ $parking->status }}</td>
            </tr>
        @endforeach

        <tr class="totals-row">
            <td colspan="4" class="text-center"><strong>TOTAL</strong></td>
            <td>{{ number_format($totalExpectedSale, 2) }}</td>
            <td>{{ number_format($totalSaleAmount, 2) }}</td>
            <td>{{ number_format($totalDifference, 2) }}</td>
            <td></td>
        </tr>
    </tbody>
</table>
</div>
</body>

</html>