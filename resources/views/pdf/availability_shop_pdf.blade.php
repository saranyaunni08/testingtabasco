<!DOCTYPE html>
<html>
<head>
    <title>Availability Shop PDF</title>
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
            font-weight: bold;
        }
        .title-row th {
            font-size: 20px;
            font-weight: bold;
            padding: 10px;
        }
        tfoot td {
            font-weight: bold;
            border: 1px solid #000;
            text-align: right;
            padding: 10px;
        }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr class="title-row">
                <th colspan="6">AVAILABILITY SHOP</th>
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
            @foreach ($availability as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item['room_floor'] }}</td>
                    <td>{{ $item['room_type'] }}</td>
                    <td>{{ $item['room_number'] }}</td>
                    <td>{{ $item['build_up_area'] }}</td>
                    <td>{{ $item['carpet_area'] }}</td>
                </tr>
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
