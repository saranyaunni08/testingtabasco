@extends('layouts.default', ['title' => 'Edit Room'])

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-info border-radius-lg p-3">
                        <h6 class="text-white text-capitalize">Edit Room</h6>
                        <form action="{{ route('admin.edit_delete_auth.logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <!-- Capture the current URL -->
                            <input type="hidden" name="redirect_url" value="{{ url()->current() }}">
                            <button type="submit" class="btn btn-danger">Logout</button>
                        </form>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <form action="{{ route('admin.rooms.update', $room->id) }}" method="POST">
                        <input type="hidden" name="building_id" value="{{ $room->building_id }}">

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
                                    <h5 class="form-label">Room Floor</h5>
                                    <input type="text" name="room_floor" class="form-control" style="text-transform: capitalize" 
                                           value="{{ old('room_floor', $room->room_floor) }}">
                                    
                                    @if ($errors->has('room_floor'))
                                        <span class="text-danger">{{ $errors->first('room_floor') }}</span>
                                    @endif
                                </div>
                                
                                <div class="form-group mb-4">
                                    <h5 class="form-label">Room Number</h5>
                                    <input type="text" name="room_number" class="form-control" style="text-transform: capitalize" value="{{ $room->room_number }}">
                                </div>
                                <div class="form-group mb-4">
                                    <h5 class="form-label">Flat Model</h5>
                                    <input type="text" name="flat_model" class="form-control" value="{{ $room->flat_model }}">
                                </div>
                                <div class="form-group mb-4">
                                    <h5 class="form-label">Carpet Area (sq ft)</h5>
                                    <input type="text" id="flat_carpet_area" name="flat_carpet_area" class="form-control" value="{{ $room->flat_carpet_area }}">
                                </div>
                                <div class="form-group mb-4">
                                    <h5 class="form-label">Carpet Area Price</h5>
                                    <input type="text" id="flat_carpet_area_price" name="flat_carpet_area_price" class="form-control" value="{{ $room->flat_carpet_area_price }}">
                                </div>
                                <div class="form-group mb-4">
                                    <h5 class="form-label">Super Build Up Area (sq ft)</h5>
                                    <input type="text" id="flat_build_up_area" name="flat_build_up_area" class="form-control" value="{{ $room->flat_build_up_area }}">
                                </div>
                                <div class="form-group mb-4">
                                    <h5 class="form-label">Super Build Up Area Price (sq ft)</h5>
                                    <input type="text" id="flat_super_build_up_price" name="flat_super_build_up_price" class="form-control" value="{{ $room->flat_super_build_up_price }}">
                                </div>

                               
                            </div>

                            <!-- Shops Fields -->
                            <div class="col-md-6" id="shopsFields" style="display: {{ $room->room_type == 'Shops' ? 'block' : 'none' }};">
                                <div class="form-group mb-4">
                                    <h5 class="form-label">Room Floor</h5>
                                    <input type="text" name="room_floor" class="form-control" style="text-transform: capitalize" 
                                           value="{{ old('room_floor', $room->room_floor) }}">
                                    
                                    @if ($errors->has('room_floor'))
                                        <span class="text-danger">{{ $errors->first('room_floor') }}</span>
                                    @endif
                                </div>
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
                                    <input type="text" id="carpet_area" name="carpet_area" class="form-control" value="{{ $room->carpet_area }}">
                                </div>
                                <div class="form-group mb-4">
                                    <h5 class="form-label">Shop Carpet Area Rate</h5>
                                    <input type="text" id="carpet_area_price" name="carpet_area_price" class="form-control" value="{{ $room->carpet_area_price }}">
                                </div>
                                <div class="form-group mb-4">
                                    <h5 class="form-label">Shop Super Build-Up Area (sq ft)</h5>
                                    <input type="text" id="build_up_area" name="build_up_area" class="form-control" value="{{ $room->build_up_area }}">
                                </div>
                                <div class="form-group mb-4">
                                    <h5 class="form-label">Shop Build-Up Area Rate</h5>
                                    <input type="text" id="super_build_up_price" name="super_build_up_price" class="form-control" value="{{ $room->super_build_up_price }}">
                                </div>
                              
                            </div>

                            <!-- Table Space Fields -->
                            <div class="col-md-6" id="tableSpaceFields" style="display: {{ $room->room_type == 'Table space' ? 'block' : 'none' }};">
                                <div class="form-group mb-4">
                                    <h5 class="form-label">Room Floor</h5>
                                    <input type="text" name="room_floor" class="form-control" style="text-transform: capitalize" 
                                           value="{{ old('room_floor', $room->room_floor) }}">
                                    
                                    @if ($errors->has('room_floor'))
                                        <span class="text-danger">{{ $errors->first('room_floor') }}</span>
                                    @endif
                                </div>
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
                                    <h5 class="form-label">Room Floor</h5>
                                    <input type="text" name="room_floor" class="form-control" style="text-transform: capitalize" 
                                           value="{{ old('room_floor', $room->room_floor) }}">
                                    
                                    @if ($errors->has('room_floor'))
                                        <span class="text-danger">{{ $errors->first('room_floor') }}</span>
                                    @endif
                                </div>
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
                                    <h5 class="form-label">Room Floor</h5>
                                    <input type="text" name="room_floor" class="form-control" style="text-transform: capitalize" 
                                           value="{{ old('room_floor', $room->room_floor) }}">
                                    
                                    @if ($errors->has('room_floor'))
                                        <span class="text-danger">{{ $errors->first('room_floor') }}</span>
                                    @endif
                                </div>
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
                                    <h5 class="form-label">Chair Space (sq ft)</h5>
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

                        @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function updateExpectedAmounts() {

            // Flat calculations
            const flatCarpetArea = parseFloat(document.getElementById('flat_carpet_area').value) || 0;
            const flatCarpetAreaPrice = parseFloat(document.getElementById('flat_carpet_area_price').value) || 0;
            const flatBuildUpArea = parseFloat(document.getElementById('flat_build_up_area').value) || 0;
            const flatSuperBuildUpPrice = parseFloat(document.getElementById('flat_super_build_up_price').value) || 0;
            const flatExpectedCarpetAreaPrice = flatCarpetArea * flatCarpetAreaPrice;
            const flatExpectedSuperBuildUpArea = flatBuildUpArea * flatSuperBuildUpPrice;

            // Shops calculations
            const carpetArea = parseFloat(document.getElementById('carpet_area').value) || 0;
            const carpetAreaPrice = parseFloat(document.getElementById('carpet_area_price').value) || 0;
            const buildUpArea = parseFloat(document.getElementById('build_up_area').value) || 0;
            const superBuildUpPrice = parseFloat(document.getElementById('super_build_up_price').value) || 0;
            const expectedCarpetAreaPrice = carpetArea * carpetAreaPrice;
            const expectedSuperBuildUpArea = buildUpArea * superBuildUpPrice;


            // chair_space calculations
            
            const chairbuildUpArea = parseFloat(document.getElementById('chair_space_in_sq').value) || 0;
            const chairsuperBuildUpPrice = parseFloat(document.getElementById('chair_space_rate').value) || 0;
            const chairexpectedSuperBuildUpArea = chairbuildUpArea * chairsuperBuildUpPrice;

            // Update Flat fields
            document.getElementById('flat_expected_carpet_area_price_display').value = flatExpectedCarpetAreaPrice.toFixed(2);
            document.getElementById('flat_expected_super_build_up_area_display').value = flatExpectedSuperBuildUpArea.toFixed(2);
            document.getElementById('flat_expected_carpet_area_price').value = flatExpectedCarpetAreaPrice.toFixed(2);
            document.getElementById('flat_expected_super_build_up_area').value = flatExpectedSuperBuildUpArea.toFixed(2);

            // Update Shops fields
            document.getElementById('expected_carpet_area_price_display').value = expectedCarpetAreaPrice.toFixed(2);
            document.getElementById('expected_super_build_up_area_display').value = expectedSuperBuildUpArea.toFixed(2);
            document.getElementById('expected_carpet_area_price').value = expectedCarpetAreaPrice.toFixed(2);
            document.getElementById('expected_super_build_up_area').value = expectedSuperBuildUpArea.toFixed(2);
        }

        // Corrected querySelectorAll with all selectors in a single string
        const inputs = document.querySelectorAll('#flat_carpet_area, #flat_carpet_area_price, #flat_build_up_area, #flat_super_build_up_price, #carpet_area, #carpet_area_price, #build_up_area, #super_build_up_price');
        
        // Attach event listeners to all relevant inputs
        inputs.forEach(input => input.addEventListener('input', updateExpectedAmounts));
    });
</script>
