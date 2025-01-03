@extends('layouts.default')

@section('content')

<div class="container">

<div style="text-align: right;">
        <a href="{{ route('admin.commercial_break_up.pdf', $building->id) }}" class="btn btn-primary">
            <i class="fas fa-arrow-down"></i> Download PDF
        </a>

    </div>

    
<!-- Commercial Spaces Section -->
<table class="table table-bordered table-striped mb-5">
        <thead>
            <tr style="background-color: #FFD700;">
                <th colspan="11" class="text-center" style="color: black; font-size: 1.5rem;">COMMERCIAL DETAILED
                    BREAKUP</th>
            </tr>
            <tr style="background-color: #FFD700;">
                <th>FLOOR</th>
                <th>TYPE</th>
                <th>SHOP NO</th>
                <th>BUILT-UP AREA (In Sq Ft)</th>
                <th>CARPET AREA (In Sq Ft)</th>
                <th>₹ EXPECTED / SQ.FT</th>
                <th>₹ EXPECTED SALE</th>
                <th>₹ SALE AMOUNT</th>
                <th>₹ SALE / SQ.FT</th>
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
                            <td>₹ {{ number_format($space->super_build_up_price, 2) }}</td> <!-- Display expected_per_sqft -->
                            <td>₹ {{ number_format($space->expected_amount, 2) }}</td>
                            <td>₹ {{ number_format($space->total_amount, 2) }}</td>
                            <td>₹ {{ number_format($space->sale_amount, 2) }}</td>
                            <td style="color: {{ $difference < 0 ? 'red' : 'green' }};">₹
                                {{ number_format($difference, 2) }}
                            </td>
                            <td style="color: {{ $space->status == 'SOLD' ? 'green' : 'blue' }};">{{ $space->status }}</td>
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
                <td>₹ {{ number_format($totalExpectedAmount, 2) }}</td>
                <td>₹ {{ number_format($totalSaleAmount, 2) }}</td>
                <td></td>
                <td>₹ {{ number_format($totalDifference, 2) }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>

@endsection
