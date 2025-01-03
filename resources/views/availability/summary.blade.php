@extends('layouts.default')

@section('content')
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f8f9fa;
    }

    .table-container {
        width: 80%;
        margin: 20px auto;
        background: #ffffff;
        padding: 20px;
        border: 1px solid #ddd;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    h2 {
        text-align: center;
        background-color: #008080;
        color: #ffffff;
        padding: 10px;
        border-radius: 4px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th,
    td {
        text-align: center;
        padding: 10px;
        border: 1px solid #ddd;
    }

    th {
        background-color: #008080;
        color: #ffffff;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tfoot {
        font-weight: bold;
    }
</style>

<div class="table-container">
    <h2>Availability Summary</h2>
    <table>
        <thead>
            <tr>
                <th>Type</th>
                <th>Nos</th>
                <th>Built-Up Area (In Sq Ft)</th>
                <th>Carpet Area (In Sq Ft)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Shops</td>
                <td>{{ $totalShops }}</td>
                <td>{{ number_format($totalBuildUpArea, 2) }}</td>
                <td>{{ number_format($totalCarpetArea, 2) }}</td>
            </tr>
            <tr>
                <td>Flats</td>
                <td>{{ $totalFlats }}</td> <!-- Last serial number is the total count of flats -->
                <td>{{ number_format($totalFlatBuildUpArea, 2) }}</td> <!-- Total build-up area for flats -->
                <td>{{ number_format($totalFlatCarpetArea, 2) }}</td> <!-- Total carpet area for flats -->
            </tr>

            <tr>
                <td>Parking</td>
                <td>{{ $totalparking }}</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Counter</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td>Total</td>
                <td>{{$totalnos}}</td>
                <td>{{ number_format($totalbuildup, 2) }}</td> <!-- Total build-up area for flats -->
                <td>{{ number_format($totalcarpet, 2) }}</td> <!-- Total carpet area for flats -->
            </tr>
        </tfoot>
    </table>
</div>
@endsection