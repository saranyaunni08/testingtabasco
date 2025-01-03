<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changes in Expected Amount</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        font-size: 12px;
        margin: 0;
        padding: 0;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    th, td {
        border: 1px solid #000;
        padding: 5px;
        text-align: left;
    }
    th {
        font-weight: bold;
        text-align: center; /* Ensures all th content is centered */
    }
    .text-center {
        text-align: center;
    }
    h3 {
        text-align: center; /* Center-aligns the h3 heading */
        margin-top: 20px;  /* Adds some space above the heading */
    }
    </style>
</head>
<body>
    <h3 class="text-center">Changes in Expected Amount</h3>
    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>FLOOR</th>
                <th>TYPE</th>
                <th>DOOR NO</th>
                <th>BUILT UP AREA (In Sq Ft)</th>
                <th>CARPET AREA (In Sq Ft)</th>
                <th>EXPECTED PER SQFT (OLD)</th>
                <th>CHANGED ON</th>
                <th>EXPECTED PER SQFT (NEW)</th>
                <th>EXPECTED</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($groupedData as $floor => $rooms)
                <tr class="table-secondary">
                <td colspan="10" class="text-center">{{ $floor }}</td>
                </tr>
                @foreach ($rooms as $room)
                    <tr>
                        <td>{{ $room->id }}</td>
                        <td>{{ $room->room_floor }}</td>
                        <td>{{ $room->room_type }}</td>
                        <td>{{ $room->room_number }}</td>
                        <td>{{ $room->build_up_area }}</td>
                        <td>{{ $room->carpet_area }}</td>
                        <td>{{ number_format($room->expected_amount, 0) }}</td>
                        <td>{{ $room->created_at ? \Carbon\Carbon::parse($room->created_at)->format('d-m-Y') : '-' }}</td>
                        <td>{{ number_format($room->sale_amount, 0) }}</td>
                        <td>{{ number_format($room->build_up_area * $room->expected_amount, 0) }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>
