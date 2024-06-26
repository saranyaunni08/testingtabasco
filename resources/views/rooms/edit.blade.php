@extends('layouts.default', ['title' => 'Edit Room'])

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-info border-radius-lg p-3">
                        <h6 class="text-white text-capitalize">Edit Room</h6>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <form action="{{ route('admin.rooms.update', $room->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="p-4">
                            <div class="form-group mb-4">
                                <h5 class="form-label">Room Type</h5>
                                <input type="text" class="form-control" id="room_type" name="room_type" value="{{ $room->room_type }}" readonly>
                            </div>

                            <!-- Flat Fields -->
                            <div class="col-md-6" id="flatFields" style="display: {{ $room->room_type == 'Flat' ? 'block' : 'none' }};">
                                <div class="form-group mb-4">
                                    <h5 class="form-label">Room Number</h5>
                                    <input type="text" name="room_number" class="form-control" style="text-transform: capitalize" value="{{ $room->room_number }}">
                                </div>
                                <div class="form-group mb-4">
                                    <h5 class="form-label">Flat model</h5>
                                    <input type="text" name="flat_model" class="form-control" value="{{ $room->flat_model }}">
                                </div>
                                <div class="form-group mb-4">
                                    <h5 class="form-label">Carpet Area (sq ft)</h5>
                                    <input type="text" name="flat_carpet_area" class="form-control" value="{{ $room->flat_carpet_area }}">
                                </div>
                                
                                <div class="form-group mb-4">
                                    <h5 class="form-label">Carpet Area Price</h5>
                                    <input type="text" name="flat_carpet_area_price" class="form-control" value="{{ $room->flat_carpet_area_price }}">
                                </div>
                               
                            </div>

                            <!-- Shops Fields -->
                            <div class="col-md-6" id="shopsFields" style="display: {{ $room->room_type == 'Shops' ? 'block' : 'none' }};">
                                <div class="form-group mb-4">
                                    <h5 class="form-label">Shop Number</h5>
                                    <input type="text" name="room_number" class="form-control" value="{{ $room->room_number }}">
                                </div>
                                <div class="form-group mb-4">
                                    <h5 class="form-label">Shop Type</h5>
                                    <select name="shop_type" class="form-select">
                                        <option value="" disabled selected>Select Shop Type</option>
                                        <option value="Retail" {{ $room->shop_type == 'Retail' ? 'selected' : '' }}>Retail Shop</option>
                                        <option value="Restaurant" {{ $room->shop_type == 'Restaurant' ? 'selected' : '' }}>Restaurant</option>
                                        <option value="Office" {{ $room->shop_type == 'Office' ? 'selected' : '' }}>Office</option>
                                    </select>
                                </div>
                                <div class="form-group mb-4">
                                    <h5 class="form-label">Shop Carpet Area (sq ft)</h5>
                                    <input type="text" name="carpet_area" class="form-control" value="{{ $room->carpet_area }}">
                                </div>
                                <div class="form-group mb-4">
                                    <h5 class="form-label">Shop Carpet Area Rate</h5>
                                    <input type="text" name="carpet_area_price'" class="form-control" value="{{ $room->carpet_area_price }}">
                                </div>
                                
                            </div>

                            {{-- <!-- Car Parking Fields -->
                            <div class="col-md-6" id="carParkingFields" style="display: {{ $room->room_type == 'Car parking' ? 'block' : 'none' }};">
                                <div class="form-group mb-4">
                                    <h5 class="form-label">Parking Number</h5>
                                    <input type="text" name="parking_number" class="form-control" value="{{ $room->parking_number }}">
                                </div>
                                <div class="form-group mb-4">
                                    <h5 class="form-label">Parking Type</h5>
                                    <select name="parking_type" class="form-select">
                                        <option value="" disabled selected>Select Parking Type</option>
                                        <option value="Covered" {{ $room->parking_type == 'Covered' ? 'selected' : '' }}>Covered Parking</option>
                                        <option value="Open" {{ $room->parking_type == 'Open' ? 'selected' : '' }}>Open Parking</option>
                                        <option value="Underground" {{ $room->parking_type == 'Underground' ? 'selected' : '' }}>Underground Parking</option>
                                    </select>
                                </div>
                                <div class="form-group mb-4">
                                    <h5 class="form-label">Parking Area (sq ft)</h5>
                                    <input type="text" name="parking_area" class="form-control" value="{{ $room->parking_area }}">
                                </div>
                                <div class="form-group mb-4">
                                    <h5 class="form-label">Parking Rate</h5>
                                    <input type="text" name="parking_rate" class="form-control" value="{{ $room->parking_rate }}">
                                </div>
                            </div> --}}

                            <!-- Table Space Fields -->
                            <div class="col-md-6" id="tableSpaceFields" style="display: {{ $room->room_type == 'Table space' ? 'block' : 'none' }};">
                                <div class="form-group mb-4">
                                    <h5 class="form-label">Space Name</h5>
                                    <input type="text" name="space_name" class="form-control" value="{{ $room->space_name }}">
                                </div>
                                <div class="form-group mb-4">
                                    <h5 class="form-label">Space Type</h5>
                                    <select name="space_type" class="form-select">
                                        <option value="" disabled selected>Select Space Type</option>
                                        <option value="Massage Chair" {{ $room->space_type == 'Massage Chair' ? 'selected' : '' }}>Massage Chair Space</option>
                                        <option value="Table Space" {{ $room->space_type == 'Table Space' ? 'selected' : '' }}>Table Space</option>
                                        <!-- Add more space types as needed -->
                                    </select>
                                </div>
                                <div class="form-group mb-4">
                                    <h5 class="form-label">Space Area (sq ft)</h5>
                                    <input type="text" name="space_area" class="form-control" value="{{ $room->space_area }}">
                                </div>
                                <div class="form-group mb-4">
                                    <h5 class="form-label">Space Rate</h5>
                                    <input type="text" name="space_rate" class="form-control" value="{{ $room->space_rate }}">
                                </div>
                            </div>

                            <!-- Kiosk Fields -->
                            <div class="col-md-6" id="kioskFields" style="display: {{ $room->room_type == 'Kiosk' ? 'block' : 'none' }};">
                                <div class="form-group mb-4">
                                    <h5 class="form-label">Kiosk Name</h5>
                                    <input type="text" name="kiosk_name" class="form-control" value="{{ $room->kiosk_name }}">
                                </div>
                                <div class="form-group mb-4">
                                    <h5 class="form-label">Kiosk Type</h5>
                                    <select name="kiosk_type" class="form-select">
                                        <option value="" disabled selected>Select Kiosk Type</option>
                                        <option value="Food Kiosk" {{ $room->kiosk_type == 'Food Kiosk' ? 'selected' : '' }}>Food Kiosk</option>
                                        <option value="Retail Kiosk" {{ $room->kiosk_type == 'Retail Kiosk' ? 'selected' : '' }}>Retail Kiosk</option>
                                        <option value="Information Kiosk" {{ $room->kiosk_type == 'Information Kiosk' ? 'selected' : '' }}>Information Kiosk</option>
                                        <!-- Add more kiosk types as needed -->
                                    </select>
                                </div>
                                <div class="form-group mb-4">
                                    <h5 class="form-label">Kiosk Area (sq ft)</h5>
                                    <input type="text" name="kiosk_area" class="form-control" value="{{ $room->kiosk_area }}">
                                </div>
                                <div class="form-group mb-4">
                                    <h5 class="form-label">Kiosk Rate</h5>
                                    <input type="text" name="kiosk_rate" class="form-control" value="{{ $room->kiosk_rate }}">
                                </div>
                            </div>

                            <!-- Chair Space Fields -->
                            <div class="col-md-6" id="chairSpaceFields" style="display: {{ $room->room_type == 'Chair space' ? 'block' : 'none' }};">
                                <div class="form-group mb-4">
                                    <h5 class="form-label">Chair Name</h5>
                                    <input type="text" name="chair_name" class="form-control" value="{{ $room->chair_name }}">
                                </div>
                                <div class="form-group mb-4">
                                    <h5 class="form-label">Chair Type</h5>
                                    <select name="chair_type" class="form-select">
                                        <option value="" disabled selected>Select Chair Type</option>
                                        <option value="Executive Chair" {{ $room->chair_type == 'Executive Chair' ? 'selected' : '' }}>Executive Chair</option>
                                        <option value="Recliner Chair" {{ $room->chair_type == 'Recliner Chair' ? 'selected' : '' }}>Recliner Chair</option>
                                        <!-- Add more chair types as needed -->
                                    </select>
                                </div>
                                <div class="form-group mb-4">
                                    <h5 class="form-label">Chair Space</h5>
                                    <input type="text" name="chair_space_in_sq" class="form-control" value="{{ $room->chair_space_in_sq }}">
                                </div>
                                <div class="form-group mb-4">
                                    <h5 class="form-label">Chair Space Rate</h5>
                                    <input type="text" name="chair_space_rate" class="form-control" value="{{ $room->chair_space_rate }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
