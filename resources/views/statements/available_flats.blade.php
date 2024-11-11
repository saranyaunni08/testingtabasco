@extends('layouts.default')

@section('content')
<div class="container my-4">
    <h1 class="text-center mb-4">Available Flats in {{ $building->name }}</h1>

    <div class="d-flex justify-content-center mb-4 gap-3">
        {{-- Updated link specifically for flats --}}
        <a href="{{ route('admin.available.flats', ['building' => $building->id]) }}" class="btn btn-outline-primary">Availability-Flats</a>
    </div>

    @if($availableRooms->isEmpty())
        <div class="alert alert-info text-center">
            No available flats in this building.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered">
                <thead class="table-dark sticky-top">
                    <tr>
                        <th class="text-center" style="width: 5%;">NO</th>
                        <th class="text-center" style="width: 10%;">FLOOR</th>
                        <th class="text-center" style="width: 10%;">DOOR NO</th>
                        <th class="text-center" style="width: 20%;">BUILT UP AREA (Sq Ft)</th>
                        <th class="text-center" style="width: 20%;">CARPET AREA (Sq Ft)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($availableRooms as $index => $room)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">{{ $room->room_floor }}</td>
                            <td class="text-center">{{ $room->door_no }}</td>
                            <td class="text-center">{{ $room->built_up_area }}</td>
                            <td class="text-center">{{ $room->carpet_area }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
