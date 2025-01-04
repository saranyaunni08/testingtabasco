<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Availability Summary PDF</title>
    <style>
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid #000;
        text-align: center;
        padding: 8px;
    }
    th {
        background-color: #fff; /* Set background to white */
        font-weight: bold;     /* Use bold text for headers */
    }
    .title-row th {
        background-color: #fff; /* White background for title row */
        color: #000;            /* Black text for title */
        font-size: 20px;
        font-weight: bold;      /* Make title stand out */
        padding: 10px;
    }
    tfoot td {
        font-weight: bold;
        border: 1px solid #000; /* Ensure visible borders for totals */
        text-align: right;
        padding: 10px;
    }
</style>
</head>
<body>
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
                <td>{{ $totalCounter }}</td>
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

</body>
</html>