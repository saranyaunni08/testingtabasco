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
        @foreach ($apartmentSalesData->sortBy('room_floor') as $row) <!-- Sorting by room_floor -->
        <tr>
            <td>{{ $row->room_floor }}</td>
            <td>{{ $row->room_number }}</td>
            <td>{{ $row->room_type }}</td>
            <td>{{ number_format($row->flat_build_up_area) }}</td>
            <td>{{ number_format($row->sales_amount) }}</td>
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


</div>
@endsection