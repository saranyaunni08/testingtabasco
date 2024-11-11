<!-- resources/views/statements/available_shops.blade.php -->
@extends('layouts.default')

@section('content')
<div class="container my-4">
    <h1 class="text-center mb-4">Available Shops in {{ $building->name }}</h1>

    @if($availableShops->isEmpty())
        <div class="alert alert-info text-center">
            No available shops in this building.
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
                    @php $currentFloor = ''; @endphp
                    @foreach($availableShops as $index => $shop)
                        <tr @if($shop->room_floor !== $currentFloor) class="table-primary" @endif>
                            <td class="text-center">{{ $index + 1 }}</td>

                            <!-- Display floor name only when it changes -->
                            <td class="text-center">
                                @if($shop->room_floor !== $currentFloor)
                                    {{ $shop->room_floor }}
                                    @php $currentFloor = $shop->room_floor; @endphp
                                @endif
                            </td>

                            <td class="text-center">{{ $shop->room_number }}</td>
                            <td class="text-end">{{ number_format($shop->build_up_area, 2) }}</td>
                            <td class="text-end">{{ number_format($shop->carpet_area, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
