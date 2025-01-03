@extends('layouts.default')

@section('content')
<div class="container">

    <div style="text-align: right;">
        <a href="{{ route('admin.changes_in_expected_amount.pdf', $building->id) }}" class="btn btn-primary">
            <i class="fas fa-arrow-down"></i> Download PDF
        </a>

    </div>
    <h3 class="text-center my-4">CHANGES IN EXPECTED AMOUNT</h3>
    <table class="table table-bordered">
        <thead class="table-primary text-center">
            <tr>
                <th>NO</th>
                <th>FLOOR</th>
                <th>TYPE</th>
                <th>DOOR NO</th>
                <th>BUILT UP AREA (In Sq Ft)</th>
                <th>CARPET AREA (In Sq Ft)</th>
                <th>₹ EXPECTED PER SQFT (OLD)</th>
                <th>CHANGED ON</th>
                <th>₹ EXPECTED PER SQFT (NEW)</th>
                <th>₹ EXPECTED</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($groupedData as $floor => $rooms)
                <!-- Floor Section -->
                <tr class="table-secondary">
                    <td colspan="10" class="text-center font-weight-bold">{{ $floor }}</td>
                </tr>
                @foreach ($rooms as $index => $room)
                    <tr class="text-center">
                        <td>{{ $room->id }}</td>
                        <td>{{ $room->room_floor }}</td>
                        <td>{{ $room->room_type }}</td>
                        <td>{{ $room->room_number }}</td>
                        <td>{{ $room->build_up_area }}</td>
                        <td>{{ $room->carpet_area }}</td>
                        <td>{{ number_format($room->expected_amount, 0) }}</td>
                        <td>{{ $room->created_at ? \Carbon\Carbon::parse($room->created_at)->format('d-m-Y') : '-' }}</td>
                        <td>{{ number_format($room->sale_amount, 0) }}</td>
                        <td>
                            {{ number_format($room->build_up_area * $room->expected_amount, 0) }}
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>
@endsection