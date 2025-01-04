<!DOCTYPE html>
<html>

<head>
    <title>Total Availability PDF</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        font-size: 12px;
        margin: 20px;
        padding: 0;
    }

    .title {
        text-align: center;
        font-size: 18px;
        margin-bottom: 10px;
        font-weight: bold;
        text-transform: uppercase;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        table-layout: fixed;
    }

    th,
    td {
        border: 1px solid #000;
        padding: 6px;
        text-align: center;
        word-wrap: break-word;
    }

    th {
        font-weight: bold;
        text-transform: uppercase;
    }

    td {
        font-size: 11px;
    }

    /* Specific styles for Counter Table */
    table.counter-table th,
    table.counter-table td {
        /* No specific styles for Counter Table to ensure a uniform appearance */
    }

    /* Specific styles for Parking Table */
    table.parking-table th,
    table.parking-table td {
        /* No specific styles for Parking Table to ensure a uniform appearance */
    }

    /* Adjustments for better print alignment */
    @page {
        size: A4;
        margin: 20mm;
    }

    @media print {
        body {
            margin: 0;
        }
    }
</style>

</head>

<body>
    <!-- Availability Table -->
    <table>
        <thead>
            <tr>
                <th colspan="6">AVAILABILITY</th>
            </tr>
            <tr>
                <th>NO</th>
                <th>FLOOR</th>
                <th>TYPE</th>
                <th>DOOR NO</th>
                <th>BUILT-UP AREA (Sq Ft)</th>
                <th>CARPET AREA (Sq Ft)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($availability->sortBy('room_floor') as $item)
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
    </table>

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


    <!-- Counter Table -->
    <table class="counter-table">
        <thead>
            <tr>
                <th colspan="4">COUNTER</th>
            </tr>
            <tr>
                <th>NO</th>
                <th>FLOOR</th>
                <th>TYPE</th>
                <th>COUNTER NUMBER</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($counterRooms->sortBy('room_floor') as $index => $room) <!-- Sorting by room_floor -->
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $room->room_floor }}</td>
                    <td>{{ $room->room_type }}</td>
                    <td>{{ $room->room_number }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">
                        No counters available.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>