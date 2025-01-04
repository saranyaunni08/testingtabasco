<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Availability Parking PDF</title>
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
    <!-- Parking Table -->
    <table class="parking-table">
        <thead>
            <tr>
                <th colspan="4">PARKING</th>
            </tr>
            <tr>
                <th>NO</th>
                <th>FLOOR</th>
                <th>TYPE</th>
                <th>PARKING NUMBER</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($parkings->sortBy('floor_number') as $item)
                @if ($item['status'] == 'available')
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item['floor_number'] }}</td>
                        <td>Parking</td>
                        <td>{{ $item['slot_number'] }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

</body>
</html>
