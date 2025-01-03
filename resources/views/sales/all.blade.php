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
<div class="d-flex justify-content-center mb-4 gap-3">
    <a href="{{ route('admin.sales.commercial', $building->id) }}" class="btn btn-outline-primary">Commercial</a>
    <a href="{{ route('admin.sales.apartment',$building->id)}}" class="btn btn-outline-secondary">Apartment</a>
    <a href="{{ route('admin.sales.parking',$building->id)}}" class="btn btn-outline-success">Parking</a>
    <a href="{{ route('admin.sales.summary',$building->id)}}" class="btn btn-outline-info">Summary</a>

  
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
        @foreach ($shopSalesData->sortBy('room_floor') as $row) <!-- Sort by room_floor -->
        <tr>
            <td>{{ $row->room_floor }}</td>
            <td>{{ $row->room_number }}</td>
            <td>{{ $row->room_type }}</td>
            <td>{{ number_format($row->build_up_area) }}</td>
            <td>{{ number_format($row->sale_amount) }}</td>
            <td>{{ number_format($row->build_up_area * $row->sale_amount, 2) }}</td>
            <td>{{ $row->customer_name }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" style="font-weight: bold;">TOTAL</td>
            <td>{{ number_format($totalShopSqft) }}</td>
            <td></td>
            <td>{{ number_format($totalShopSaleAmount) }}</td>
            <td></td>
        </tr>
    </tfoot>
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
        @foreach ($apartmentSalesData->sortBy('room_floor') as $row) <!-- Sort by room_floor -->
        <tr>
            <td>{{ $row->room_floor }}</td>
            <td>{{ $row->room_number }}</td>
            <td>{{ $row->room_type }}</td>
            <td>{{ number_format($row->flat_build_up_area) }}</td>
            <td>{{ number_format($row->sale_amount) }}</td>
            <td>{{ number_format($row->build_up_area * $row->sale_amount, 2) }}</td>
            <td>{{ $row->customer_name }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" style="font-weight: bold;">TOTAL</td>
            <td>{{ number_format($totalApartmentSqft) }}</td>
            <td></td>
            <td>{{ number_format($totalApartmentSaleAmount) }}</td>
            <td></td>
        </tr>
    </tfoot>
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

</div>
@endsection
