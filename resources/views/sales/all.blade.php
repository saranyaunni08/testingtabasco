@extends('layouts.default')

@section('content')
<style>
    body {
        font-family: Arial, sans-serif;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        margin-bottom: 20px; /* Adds space between tables */
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
<div class="d-flex justify-content-center mb-4 gap-3">
    <a href="{{ route('admin.sales.commercial', $building->id) }}" class="btn btn-outline-primary">Commercial</a>
    <a href="{{ route('admin.sales.apartment',$building->id)}}" class="btn btn-outline-secondary">Apartment</a>
    <a href="{{ route('admin.sales.parking',$building->id)}}" class="btn btn-outline-success">Parking</a>
    <a href="{{ route('admin.sales.summary',$building->id)}}" class="btn btn-outline-info">Summary</a>

  
</div>

<div style="text-align: right;">
    <a href="{{ route('admin.sales_all_report.pdf', $building->id) }}" class="btn btn-primary">
    <i class="fas fa-arrow-down"></i> Download PDF
</a>

</div>
   <!-- Commercial Sales Report -->
   <table>
    <thead>
        <tr class="title-row">
            <td colspan="7">COMMERCIAL SALES REPORT</td>
        </tr>
        <tr class="subheading">
            <th>FLOOR</th>
            <th>SHOP NO</th>
            <th>TYPE</th>
            <th>SQFT</th>
            <th>SALES PRICE</th>
            <th>TOTAL SALE AMOUNT</th>
            <th>CLIENT NAME</th>
        </tr>
    </thead>
    <tbody>
        @php
            // Group the sales data by floor
            $groupedByFloor = $shopSalesData->groupBy('room_floor');
        @endphp

        @foreach ($groupedByFloor as $floor => $floorData)
            @php
                // Calculate totals for the current floor
                $floorTotalSqft = $floorData->sum('build_up_area');
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
                    <td>{{ number_format($row->build_up_area) }}</td>
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

 <!-- Apartment Sales Report -->
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

<!-- Parking Sales Report -->
<table>
    <thead>
        <tr class="title-row">
            <td colspan="4">PARKING SALES REPORT</td>
        </tr>
        <tr class="subheading">
            <th>FLOOR</th>
            <th>PARKING NO</th>
            <th>SALES PRICE</th>
            <th>CLIENT NAME</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($parkingSalesData->sortBy('floor_number') as $sale) <!-- Sort by floor_number -->
        <tr>
            <td>{{ $sale->floor_number }}</td>
            <td>{{ $sale->parking_id }}</td>
            <td>{{ number_format($sale->sale_amount) }}</td>
            <td>{{ $sale->purchaser_name }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="1" style="font-weight: bold;">TOTAL</td>
            <td>{{ number_format($totalparkingnumber) }}</td>
            <td>{{ number_format($totalParkingSales) }}</td>
            <td></td>
        </tr>
    </tfoot>
</table>

<!-- Kiosk Sales Report -->
<table>
    <thead>
        <tr class="title-row">
            <td colspan="7">KIOSK SALES REPORT</td>
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
            $groupedByFloor = $kioskSalesData->groupBy('room_floor');
        @endphp

        @foreach ($groupedByFloor as $floor => $floorData)
            @php
                // Calculate totals for the current floor
                $floorTotalSqft = $floorData->sum('kiosk_area');
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
                <td>{{ number_format($row->kiosk_area) }}</td>
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

<!-- Tablespace Sales Report -->
<table>
    <thead>
        <tr class="title-row">
            <td colspan="7">TABLESPACE SALES REPORT</td>
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
            $groupedByFloor = $tablespaceSalesData->groupBy('room_floor');
        @endphp

        @foreach ($groupedByFloor as $floor => $floorData)
            @php
                // Calculate totals for the current floor
                $floorTotalSqft = $floorData->sum('space_area');
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
                <td>{{ number_format($row->space_area) }}</td>
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

<!-- Chairspace Sales Report -->
<table>
    <thead>
        <tr class="title-row">
            <td colspan="7">CHAIRSPACE SALES REPORT</td>
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
            $groupedByFloor = $chairspaceSalesData->groupBy('room_floor');
        @endphp

        @foreach ($groupedByFloor as $floor => $floorData)
            @php
                // Calculate totals for the current floor
                $floorTotalSqft = $floorData->sum('chair_space_in_sq');
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
                <td>{{ number_format($row->chair_space_in_sq) }}</td>
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

<!-- Counter Sales Report -->
<table>
    <thead>
        <tr class="title-row">
            <td colspan="7">COUNTER SALES REPORT</td>
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
            // Group the counter sales data by floor
            $groupedByFloor = $counterSalesData->groupBy('room_floor');
        @endphp

        @foreach ($groupedByFloor as $floor => $floorData)
            @php
                // Calculate totals for the current floor
                $floorTotalCustomArea = $floorData->sum('custom_area');
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
                    <td>{{ number_format($row->custom_area, 2) }}</td>
                    <td>{{ number_format(optional($row->sale)->sale_amount, 2) }}</td>
                    <td>{{ number_format(optional($row->sale)->total_amount, 2) }}</td>
                    <td>{{ optional($row->sale)->customer_name }}</td>
                </tr>
            @endforeach

            <!-- Display total for the current floor -->
            <tr>
                <td colspan="3" style="font-weight: bold;">TOTAL</td>
                <td>{{ number_format($floorTotalCustomArea, 2) }}</td>
                <td></td>
                <td>{{ number_format($floorTotalSaleAmount, 2) }}</td>
                <td></td>
            </tr>
        @endforeach
    </tbody>
</table>


</div>
@endsection
