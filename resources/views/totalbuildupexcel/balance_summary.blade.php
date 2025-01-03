@extends('layouts.default')

@section('content')


<div class="container mt-5">

<div style="text-align: right;">
        <a href="{{ route('admin.balance_summary_break_up.pdf', $building->id) }}" class="btn btn-primary">
            <i class="fas fa-arrow-down"></i> Download PDF
        </a>

    </div>

    <div class="card">
        <div class="card-header bg-primary text-white text-center">
            <h5 class="mb-0">BALANCE SQFT AND FINANCIAL SUMMARY</h5>
        </div>
        <div class="card-body">
           <!-- Room Data Table -->
<table class="table table-bordered text-center">
    <thead class="table-success">
        <tr>
            <th>TYPE</th>
            <th>TOTAL SQ FT</th>
            <th>SALES SQFT</th>
            <th>SALE AMOUNT</th>
            <th>BALANCE SQFT</th>
            <th>EXPECTED BALANCE AMOUNT</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalSqFtAll = 0;
            $salesSqFtAll = 0;
            $saleAmountAll = 0;
            $balanceSqFtAll = 0;
            $expectedBalanceAmountAll = 0;
        @endphp
        
        @foreach ($roomTypeData as $room)
            <tr>
                <td>{{ strtoupper($room['type']) }}</td>
                <td>{{ number_format($room['totalSqFt'], 2) }}</td>
                <td>{{ number_format($room['salesSqFt'], 2) }}</td>
                <td>₹{{ number_format($room['saleAmount'], 2) }}</td>
                <td>{{ number_format($room['balanceSqFt'], 2) }}</td>
                <td>₹{{ number_format($room['expectedBalanceAmount'], 2) }}</td>
            </tr>

            @php
                $totalSqFtAll += $room['totalSqFt'];
                $salesSqFtAll += $room['salesSqFt'];
                $saleAmountAll += $room['saleAmount'];
                $balanceSqFtAll += $room['balanceSqFt'];
                $expectedBalanceAmountAll += $room['expectedBalanceAmount'];
            @endphp
        @endforeach

        <tr class="fw-bold">
            <td>TOTAL</td>
            <td>{{ number_format($totalSqFtAll, 2) }}</td>
            <td>{{ number_format($salesSqFtAll, 2) }}</td>
            <td>₹{{ number_format($saleAmountAll, 2) }}</td>
            <td>{{ number_format($balanceSqFtAll, 2) }}</td>
            <td>₹{{ number_format($expectedBalanceAmountAll, 2) }}</td>
        </tr>
    </tbody>
</table>

<!-- Parking Data Table -->
<table class="table table-bordered text-center mt-4">
    <thead class="table-primary">
        <tr>
        <th>TYPE</th>
            <th>TOTAL SQ FT</th>
            <th>SALES SQFT</th>
            <th>SALE AMOUNT</th>
            <th>BALANCE SQFT</th>
            <th>EXPECTED BALANCE AMOUNT</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ strtoupper($parkingData['type']) }}</td>
            <td>{{ number_format($parkingData['totalSqFt'], 2) }}</td>
            <td>{{ number_format($parkingData['salesSqFt'], 2) }}</td>
            <td>₹{{ number_format($parkingData['saleAmount'], 2) }}</td>
            <td>{{ number_format($parkingData['balanceSqFt'], 2) }}</td>
            <td>₹{{ number_format($parkingData['expectedBalanceAmount'], 2) }}</td>
        </tr>
    </tbody>
</table>

        </div>
    </div>
</div>



</div>
@endsection