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

<div class="container">
<div style="text-align: right;">
    <a href="{{ route('admin.sales_parking_report.pdf', $building->id) }}" class="btn btn-primary">
    <i class="fas fa-arrow-down"></i> Download PDF
</a>
</div>
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
        @foreach ($parkingSalesData->sortBy('floor_number') as $sale) <!-- Sorting by floor_number -->
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