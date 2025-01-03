@extends('layouts.default')

@section('content')
<div class="container">
    <!-- New Area Calculation Section -->
    <div class="d-flex justify-content-end mb-4">
        <div class="p-2">
            <strong>Area in Sq Ft:</strong>
            @php
                $totalAreaInSqft = $apartments->sum('flat_build_up_area') + $commercialspaces->sum('build_up_area');
            @endphp
            {{ number_format($totalAreaInSqft, 2) }}
        </div>
        <div class="p-2">
            <strong>Area in Sq M:</strong>
            @php
                $totalAreaInSqm = $totalAreaInSqft * 0.092903;
            @endphp
            {{ number_format($totalAreaInSqm, 2) }}
        </div>
    </div>

    <!-- Navigation Buttons -->
    <div class="d-flex justify-content-center mb-4 gap-3">
        <a href="{{ route('admin.totalbuildupexcel.apartment_breakup', $building->id) }}" class="btn btn-primary">
            Apartment Breakup
        </a>
        <a href="{{ route('admin.totalbuildupexcel.commercial_breakup', $building->id) }}" class="btn btn-secondary">
            Commercial Breakup
        </a>
        <a href="{{ route('admin.totalbuildupexcel.parking_breakup', $building->id) }}" class="btn btn-info">
            Parking Breakup
        </a>
        <a href="{{ route('admin.totalbuildupexcel.summary', $building->id)}}" class="btn btn-light">Summary</a>
        <a href="{{ route('admin.totalbuildupexcel.balance_summary', $building->id)}}" class="btn btn-success">Balance
            Summary</a>
        <a href="{{ route('admin.totalbuildupexcel.changes_in_expected_amount', $building->id)}}"
            class="btn btn-warning">Changes In Expected Amount</a>
    </div>

    <div style="text-align: right;">
        <a href="{{ route('admin.total_breakup.pdf', $building->id) }}" class="btn btn-primary">
            <i class="fas fa-arrow-down"></i> Download PDF
        </a>

    </div>


    <h2>{{ $building->name }} Total Build-Up Area Breakdown</h2>


    <!-- Apartments Section -->
    <table class="table table-bordered table-striped mb-5">
        <thead>
            <tr style="background-color: #ADD8E6;">
                <th colspan="11" class="text-center" style="color: white; font-size: 1.5rem;">APARTMENT DETAILED BREAKUP
                </th>
            </tr>
            <tr style="background-color: #ADD8E6;">
                <th>FLOOR</th>
                <th>TYPE</th>
                <th>DOOR NO</th>
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

            @foreach ($apartments->sortBy('room_floor') as $apartment) <!-- Sort by room_floor -->
                        @php
                            $difference = ($apartment->total_amount ?? 0) - ($apartment->expected_amount ?? 0);
                        @endphp
                        <tr>
                            <td>{{ $apartment->room_floor }}</td>
                            <td>{{ $apartment->room_type }}</td>
                            <td>{{ $apartment->room_number }}</td>
                            <td>{{ $apartment->flat_build_up_area }}</td>
                            <td>{{ $apartment->flat_carpet_area }}</td>
                            <td>₹ {{ number_format($apartment->flat_super_build_up_price, 2) }}</td>
                            <!-- Display expected_per_sqft -->
                            <td>₹ {{ number_format($apartment->expected_amount, 2) }}</td>
                            <td>₹ {{ number_format($apartment->total_amount, 2) }}</td>
                            <td>₹ {{ number_format($apartment->sale_amount, 2) }}</td>
                            <td style="color: {{ $difference < 0 ? 'red' : 'green' }};">₹
                                {{ number_format($difference, 2) }}
                            </td>
                            <td style="color: {{ $apartment->status == 'SOLD' ? 'green' : 'blue' }};">{{ $apartment->status }}</td>
                        </tr>

                        @php
                            $totalBuildUpArea += $apartment['flat_build_up_area'];
                            $totalCarpetArea += $apartment['flat_carpet_area'];
                            $totalExpectedAmount += $apartment['expected_amount'];
                            $totalSaleAmount += $apartment['sale_amount'];
                            $totalDifference += $difference; // Already calculated above with proper handling
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


    <!-- Apartment Parking Section -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr style="background-color: #6c757d;">
                <th colspan="9" class="text-center" style="color: white; font-size: 1.5rem;">APARTMENT PARKING DETAILED
                    BREAKUP</th>
            </tr>
            <tr style="background-color: #6c757d;">
                <th>FLOOR</th>
                <th>NO</th>
                <th>PARKING NO</th>
                <th>NAME</th>
                <th>₹ EXPECTED SALE</th>
                <th>₹ SALE AMOUNT</th>
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
                            <td>₹ {{ number_format($parking->amount, 2) }}</td>
                            <td>₹
                                {{ number_format(($parking->sales->parking_amount_cash ?? 0) + ($parking->sales->parking_amount_cheque ?? 0), 2) }}
                            </td>

                            <td style="color: {{ $difference < 0 ? 'red' : 'green' }};">₹
                                {{ number_format($difference, 2) }}
                            </td>
                            <td style="color: {{ $parking->status == 'SOLD' ? 'green' : 'blue' }};">{{ $parking->status }}</td>
                        </tr>
            @endforeach

            <tr style="font-weight: bold; background-color: #f8f9fa;">
                <td colspan="4" class="text-center"><strong>TOTAL</strong></td>
                <td>₹ {{ number_format($totalExpectedSale, 2) }}</td>
                <td>₹ {{ number_format($totalSaleAmount, 2) }}</td>
                <td>₹ {{ number_format($totalDifference, 2) }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>


</div>
@endsection