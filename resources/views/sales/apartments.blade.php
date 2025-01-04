@extends('layouts.default')

@section('content')
<style>
    body {
        font-family: Arial, sans-serif;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }

    th {
        background-color: #009688;
        color: white;
        font-weight: bold;
    }

    .title-row td {
        font-size: 20px;
        font-weight: bold;
        color: #ffffff;
        text-align: center;
        background-color: #009688;
        border: none;
        padding: 20px;
    }

    .subheading {
        background-color: #f2f2f2;
        color: black;
    }

    .note {
        color: red;
        font-style: italic;
        margin-top: 10px;
    }

    .footer-note {
        margin-top: 20px;
        font-size: 12px;
        font-style: italic;
        color: red;
        text-align: left;
    }
</style>
<div class="container">

<div style="text-align: right;">
    <a href="{{ route('admin.sales_apartment_report.pdf', $building->id) }}" class="btn btn-primary">
    <i class="fas fa-arrow-down"></i> Download PDF
</a>

</div>

<table>
    <thead>
        <tr class="title-row">
            <td colspan="7">APARTMENT SALES REPORT</td>
        </tr>
        <tr class="subheading">
            <th>FLOOR</th>
            <th>DOOR NO</th>
            <th>TYPE</th>
            <th>SQFT</th>
            <th>SALES PRICE</th>
            <th>TOTAL SALE AMOUNT</th>
            <th>CLIENT NAME</th>
        </tr>
    </thead>
    <tbody>
        @php
            // Group the apartment sales data by floor
            $groupedByFloor = $apartmentSalesData->groupBy('room_floor');
        @endphp

        @foreach ($groupedByFloor as $floor => $floorData)
            @php
                // Calculate totals for the current floor
                $floorTotalSqft = $floorData->sum('flat_build_up_area');
                $floorTotalSaleAmount = $floorData->sum(function ($row) {
                    return optional($row->sale)->total_amount ?: 0;
                });
            @endphp

            <!-- Display floor-wise data -->
            @foreach ($floorData as $row)
                <tr>
                    <td>{{ $row->room_floor }}</td>
                    <td>{{ $row->room_number }}</td>
                    <td>{{ $row->room_type }}</td>
                    <td>{{ number_format($row->flat_build_up_area) }}</td>
                    <td>{{ number_format($row->sales_amount) }}</td>
                    <td>{{ number_format(optional($row->sale)->total_amount, 2) }}</td>
                    <td>{{ $row->customer_name }}</td>
                </tr>
            @endforeach

            <!-- Display total for the current floor -->
            <tr>
                <td colspan="3" style="font-weight: bold;">TOTAL</td>
                <td>{{ number_format($floorTotalSqft) }}</td>
                <td></td>
                <td>{{ number_format($floorTotalSaleAmount) }}</td>
                <td></td>
            </tr>
        @endforeach
    </tbody>
</table>

</div>
@endsection