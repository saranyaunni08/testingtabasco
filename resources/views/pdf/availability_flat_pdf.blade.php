<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Availability Flat PDF</title>
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
    <table>
        <thead>
            <tr class="title-row">
                <th colspan="6">AVAILABILITY FLAT</th>
            </tr>
            <tr>
                <th>NO</th>
                <th>FLOOR</th>
                <th>TYPE</th>
                <th>DOOR NO</th>
                <th>BUILT-UP AREA (In Sq Ft)</th>
                <th>CARPET AREA (In Sq Ft)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($availability->sortBy('room_floor') as $item)
                @if ($item['room_type'] == 'Flats')
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item['room_floor'] }}</td>
                        <td>{{ $item['room_type'] }}</td>
                        <td>{{ $item['room_number'] }}</td>
                        <td>{{ $item['build_up_area'] }}</td>
                        <td>{{ $item['carpet_area'] }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4">Total</td>
                <td>{{ number_format($totalBuildUpArea) }}</td>
                <td>{{ number_format($totalCarpetArea) }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
