@extends('layouts.default', ['title' => 'Room View', 'page' => 'rooms'])

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-info border-radius-lg p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3 mb-0">Rooms</h6>
                            <div class="pe-3">
                                <a href="{{ route('admin.rooms.create') }}" class="btn btn-light m-0">Add Rooms</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="row p-4">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Room Number</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Floor</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Type</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Additional Information</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rooms as $room)
                                    <tr>
                                        <td>
                                            <h6 class="mb-0 text-xs">{{ strtoupper($room->room_number) }}</h6>
                                        </td>
                                        <td>
                                            <p class="text-xs text-dark font-weight-normal mb-0">{{ strtoupper($room->room_floor) }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs text-dark font-weight-normal mb-0">{{ strtoupper($room->room_type) }}</p>
                                        </td>
                                        <td>
                                            <!-- Conditionally display fields based on room type -->
                                            @switch($room->room_type)
                                                @case('Flat')
                                                    <p>Build-Up Area: {{ strtoupper($room->build_up_area) }}</p>
                                                    <p>Carpet Area: {{ strtoupper($room->carpet_area) }}</p>
                                                    <p>Flat Rate: {{ strtoupper($room->flat_rate) }}</p>
                                                    <p>Super Build-Up Area Price: {{ strtoupper($room->super_build_up_price) }}</p>
                                                    <p>Carpet Area Price: {{ strtoupper($room->carpet_area_price) }}</p>
                                                    <!-- Add other fields specific to Flat type -->
                                                    @break
                                                @case('Shops')
                                                    <p>Shop Number: {{ strtoupper($room->shop_number) }}</p>
                                                    <p>Shop Type: {{ strtoupper($room->shop_type) }}</p>
                                                    <p>Shop Area: {{ strtoupper($room->shop_area) }}</p>
                                                    <p>Shop Rate: {{ strtoupper($room->shop_rate) }}</p>
                                                    <p>Rental Period: {{ strtoupper($room->shop_rental_period) }}</p>
                                                    <!-- Add other fields specific to Shop type -->
                                                    @break
                                                @case('Car parking')
                                                    <p>Parking Number: {{ strtoupper($room->parking_number) }}</p>
                                                    <p>Parking Type: {{ strtoupper($room->parking_type) }}</p>
                                                    <p>Parking Area: {{ strtoupper($room->parking_area) }}</p>
                                                    <p>Parking Rate: {{ strtoupper($room->parking_rate) }}</p>
                                                    <!-- Add other fields specific to Car Parking type -->
                                                    @break
                                                @case('Table space')
                                                    <p>Space Name: {{ strtoupper($room->space_name) }}</p>
                                                    <p>Space Type: {{ strtoupper($room->space_type) }}</p>
                                                    <p>Space Area: {{ strtoupper($room->space_area) }}</p>
                                                    <p>Space Rate: {{ strtoupper($room->space_rate) }}</p>
                                                    <!-- Add other fields specific to Table Space type -->
                                                    @break
                                                @case('Kiosk')
                                                    <p>Kiosk Name: {{ strtoupper($room->kiosk_name) }}</p>
                                                    <p>Kiosk Type: {{ strtoupper($room->kiosk_type) }}</p>
                                                    <p>Kiosk Area: {{ strtoupper($room->kiosk_area) }}</p>
                                                    <p>Kiosk Rate: {{ strtoupper($room->kiosk_rate) }}</p>
                                                    <!-- Add other fields specific to Kiosk type -->
                                                    @break
                                                @case('Chair space')
                                                    <p>Chair Name: {{ strtoupper($room->chair_name) }}</p>
                                                    <p>Chair Type: {{ strtoupper($room->chair_type) }}</p>
                                                    <p>Chair Material: {{ strtoupper($room->chair_material) }}</p>
                                                    <p>Chair Price: {{ strtoupper($room->chair_price) }}</p>
                                                    <!-- Add other fields specific to Chair Space type -->
                                                    @break
                                                <!-- Add cases for other room types -->
                                            @endswitch
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.rooms.edit', $room->id) }}" class="btn btn-sm btn-primary me-1">Edit</a>
                                                <form method="POST" action="{{ route('admin.rooms.destroy', $room->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this room?')">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
