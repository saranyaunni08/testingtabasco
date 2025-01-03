@extends('layouts.default')

@section('content')
<div class="container">
<div class="d-flex justify-content-center mb-4 gap-3">
    <a href="{{ route('admin.availability.availabilityshop', $building->id) }}" class="btn btn-outline-primary">Availability Shop</a>
    <a href="{{ route('admin.availability.availabilityflat',$building->id)}}" class="btn btn-outline-secondary">Availability Flat</a>
    <a href="{{ route('admin.availability.availabilityparking',$building->id)}}" class="btn btn-outline-success">Availability Parking</a>
    <a href="{{ route('admin.availability.summary',$building->id)}}" class="btn btn-outline-info">Summary</a>

  
</div>

<div style="text-align: right;">
        <a href="{{ route('admin.total_availability.pdf', $building->id) }}" class="btn btn-primary">
            <i class="fas fa-arrow-down"></i> Download PDF
        </a>

    </div>

<!-- Table Section -->
<table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
    <thead>
        <!-- Title Row -->
        <tr>
            <th colspan="6"
                style="text-align: center; background-color: #008080; color: white; padding: 10px; border: 1px solid #000; font-size: 20px;">
                AVAILABILITY
            </th>
        </tr>
        <!-- Headers -->
        <tr>
            <th style="border: 1px solid #000; text-align: center; padding: 8px; background-color: #d9d9d9;">NO</th>
            <th style="border: 1px solid #000; text-align: center; padding: 8px; background-color: #d9d9d9;">FLOOR
            </th>
            <th style="border: 1px solid #000; text-align: center; padding: 8px; background-color: #d9d9d9;">TYPE
            </th>
            <th style="border: 1px solid #000; text-align: center; padding: 8px; background-color: #d9d9d9;">DOOR NO
            </th>
            <th style="border: 1px solid #000; text-align: center; padding: 8px; background-color: #d9d9d9;">
                BUILT-UP AREA (In Sq Ft)</th>
            <th style="border: 1px solid #000; text-align: center; padding: 8px; background-color: #d9d9d9;">CARPET
                AREA (In Sq Ft)</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($availability->sortBy('room_floor') as $item) <!-- Sorting by room_floor -->
            <tr
                style="background-color: {{ $item['room_type'] == 'Flats' ? '#d1ffd1' : ($item['room_type'] == 'Shops' ? '#ffd1d1' : '#ffffff') }};">
                <td style="border: 1px solid #000; text-align: center; padding: 8px;">{{ $loop->iteration }}</td>
                <td style="border: 1px solid #000; text-align: center; padding: 8px;">{{ $item['room_floor'] }}</td>
                <td style="border: 1px solid #000; text-align: center; padding: 8px;">{{ $item['room_type'] }}</td>
                <td style="border: 1px solid #000; text-align: center; padding: 8px;">{{ $item['room_number'] }}</td>
                <td style="border: 1px solid #000; text-align: center; padding: 8px;">{{ $item['build_up_area'] }}</td>
                <td style="border: 1px solid #000; text-align: center; padding: 8px;">{{ $item['carpet_area'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
    <thead>
        <!-- Title Row -->
        <tr>
            <th colspan="5"
                style="text-align: center; background-color: #008080; color: white; padding: 10px; border: 1px solid #000; font-size: 20px;">
                PARKING
            </th>
        </tr>
        <!-- Headers -->
        <tr>
            <th style="border: 1px solid #000; text-align: center; padding: 8px; background-color: #d9d9d9;">NO</th>
            <th style="border: 1px solid #000; text-align: center; padding: 8px; background-color: #d9d9d9;">FLOOR
            </th>
            <th style="border: 1px solid #000; text-align: center; padding: 8px; background-color: #d9d9d9;">TYPE
            </th>
            <th style="border: 1px solid #000; text-align: center; padding: 8px; background-color: #d9d9d9;">PARKING
                NUMBER</th>
            <th style="border: 1px solid #000; text-align: center; padding: 8px; background-color: #d9d9d9;">NAME
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($parkings->sortBy('floor_number') as $item) <!-- Sorting by floor_number -->
            @if ($item['status'] == 'available')
                <tr>
                    <td style="border: 1px solid #000; text-align: center; padding: 8px;">{{ $loop->iteration }}</td>
                    <td style="border: 1px solid #000; text-align: center; padding: 8px;">{{ $item['floor_number'] }}</td>
                    <td style="border: 1px solid #000; text-align: center; padding: 8px;">Parking</td>
                    <td style="border: 1px solid #000; text-align: center; padding: 8px;">{{ $item['slot_number'] }}</td>
                    <td style="border: 1px solid #000; text-align: center; padding: 8px;">{{ $item['purchaser_name'] }}</td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>


<table style="width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 16px;">
    <thead>
        <!-- Title Row -->
        <tr>
            <th colspan="5"
                style="text-align: center; background-color: #004080; color: white; padding: 15px; border: 1px solid #000; font-size: 22px;">
                COUNTER
            </th>
        </tr>
        <!-- Headers -->
        <tr>
            <th style="border: 1px solid #000; text-align: center; padding: 12px; background-color: #d9d9d9;">NO</th>
            <th style="border: 1px solid #000; text-align: center; padding: 12px; background-color: #d9d9d9;">FLOOR</th>
            <th style="border: 1px solid #000; text-align: center; padding: 12px; background-color: #d9d9d9;">TYPE</th>
            <th style="border: 1px solid #000; text-align: center; padding: 12px; background-color: #d9d9d9;">COUNTER NUMBER</th>
            <th style="border: 1px solid #000; text-align: center; padding: 12px; background-color: #d9d9d9;">NAME</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($counterRooms->sortBy('room_floor') as $index => $room) <!-- Sorting by room_floor -->
            <tr>
                <td style="border: 1px solid #000; text-align: center; padding: 10px;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #000; text-align: center; padding: 10px;">{{ $room->room_floor }}</td>
                <td style="border: 1px solid #000; text-align: center; padding: 10px;">{{ $room->custom_type }}</td>
                <td style="border: 1px solid #000; text-align: center; padding: 10px;">{{ $room->room_number }}</td>
                <td style="border: 1px solid #000; text-align: center; padding: 10px;">---</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" style="border: 1px solid #000; text-align: center; padding: 12px;">
                    No counters available.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>



</div>
@endsection