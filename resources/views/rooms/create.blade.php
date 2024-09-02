@extends('layouts.default', ['title' => 'Add Room', 'page' => 'rooms'])

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-info border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Add Room</h6>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="row p-4">
                        <form id="addRoomForm" action="{{ route('admin.rooms.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="building_id" value="{{ $building_id }}">
                            <input type="hidden" id="no_of_floors" value="{{ $building->no_of_floors }}">
                            <input type="hidden" id="result" value="{{ $result ?? '0' }}"> <!-- Add this line -->
                            <input type="hidden" id="result_carpet" value="{{ $result_carpet ?? '0' }}">


                            <div class="row justify-content-center">
                                <div class="col-lg-8">
                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <label for="room_number" class="form-label">Room Number</label>
                                            <input type="text" name="room_number" class="form-control" required style="text-transform: uppercase;">
                                        </div>

                                        <div class="col-md-6 mb-4">
                                            <label for="room_floor" class="form-label">Floor</label>
                                            <input type="text" id="room_floor" name="room_floor" class="form-control" style="text-transform: uppercase;" required>
                                            <div id="floorError" style="color: red;"></div>
                                        </div>

                                        <div class="col-md-6 mb-4">
                                            <label for="room_type" class="form-label">Room Type</label>
                                            <select id="room_type" name="room_type" class="form-select" style="text-transform: uppercase;" required>
                                                <option value="" disabled selected>Select Room Type</option>
                                                <option value="Flat" {{ $room_type == 'Flat' ? 'selected' : '' }}>Flat</option>
                                                <option value="Shops" {{ $room_type == 'Shops' ? 'selected' : '' }}>Shops</option>
                                                <option value="Table space" {{ $room_type == 'Table space' ? 'selected' : '' }}>Table space</option>
                                                <option value="Chair space" {{ $room_type == 'Chair space' ? 'selected' : '' }}>Chair space</option>
                                                <option value="Kiosk" {{ $room_type == 'Kiosk' ? 'selected' : '' }}>Kiosk</option>
                                            </select>
                                        </div>

                                        <!-- Flat Fields -->
                                        <div class="col-md-6 mb-4" id="flatFields" style="display: {{ $room_type == 'Flat' ? 'block' : 'none' }};">
                                            <label for="flat_model" class="form-label">Flat Model</label>
                                            <input type="text" name="flat_model" class="form-control" style="text-transform: uppercase;">
                                            <label for="flat_build_up_area" class="form-label mt-3">Super Build-Up Area (sq ft)</label>
                                            <input type="text" id="flat_build_up_area" name="flat_build_up_area" class="form-control" style="text-transform: uppercase;">
                                            <div id="superBuildUpError" style="color: red;"></div>
                                            
                                            <label for="flat_super_build_up_price" class="form-label mt-3">Build-Up Area Price in sq</label>
                                            <input type="text" name="flat_super_build_up_price" class="form-control" style="text-transform: uppercase;">
                                            <label for="flat_carpet_area" class="form-label mt-3">Carpet Area (sq ft)</label>
                                            <input type="text" name="flat_carpet_area" class="form-control" style="text-transform: uppercase;">
                                            <div id="CapertAreaError" style="color: red;"></div>
                                            <label for="flat_carpet_area_price" class="form-label mt-3">Carpet Area Price in Sq</label>
                                            <input type="text" name="flat_carpet_area_price" class="form-control" style="text-transform: uppercase;">
                                        </div>

                                        <!-- Shops Fields -->
                                        <div class="col-md-6 mb-4" id="shopsFields" style="display: {{ $room_type == 'Shops' ? 'block' : 'none' }};">
                                            <label for="shop_type" class="form-label">Shop Type</label>
                                            <input type="text" name="shop_type" class="form-control" style="text-transform: uppercase;">
                                            <label for="build_up_area" class="form-label mt-3">Super Build-up Area (sq ft)</label>
                                            <input type="text" name="build_up_area" class="form-control" style="text-transform: uppercase;">
                                            <div id="shopsSuperBuildUpError" style="color: red;"></div>

                                            <label for="super_build_up_price" class="form-label mt-3">Super Build up Area Rate</label>
                                            <input type="text" name="super_build_up_price" class="form-control" style="text-transform: uppercase;">
                                            <label for="carpet_area" class="form-label mt-3">Shop Carpet Area (sq ft)</label>
                                            <input type="text" name="carpet_area" class="form-control" style="text-transform: uppercase;">
                                            <div id="shopsCapertAreaError" style="color: red;"></div>

                                            <label for="carpet_area_price" class="form-label mt-3">Shop Carpet Area Rate</label>
                                            <input type="text" name="carpet_area_price" class="form-control" style="text-transform: uppercase;">
                                        </div>

                                        <!-- Table Space Fields -->
                                        <div class="col-md-6 mb-4" id="tableSpaceFields" style="display: {{ $room_type == 'Table space' ? 'block' : 'none' }};">
                                            <label for="space_name" class="form-label">Space Name</label>
                                            <input type="text" name="space_name" class="form-control" style="text-transform: uppercase;">
                                            <label for="space_type" class="form-label mt-3">Space Type</label>
                                            <select name="space_type" class="form-select" style="text-transform: uppercase;">
                                                <option value="" disabled selected>Select Space Type</option>
                                                <option value="Massage Chair">Massage Chair Space</option>
                                                <option value="Table Space">Table Space</option>
                                            </select>
                                            <label for="space_area" class="form-label mt-3">Space Area (sq ft)</label>
                                            <input type="text" name="space_area" class="form-control" style="text-transform: uppercase;">
                                            <div id="superBuildUpError" style="color: red;"></div>

                                            <label for="space_rate" class="form-label mt-3">Space Rate</label>
                                            <input type="text" name="space_rate" class="form-control" style="text-transform: uppercase;">
                                        </div>

                                        <!-- Kiosk Fields -->
                                        <div class="col-md-6 mb-4" id="kioskFields" style="display: {{ $room_type == 'Kiosk' ? 'block' : 'none' }};">
                                            <label for="kiosk_name" class="form-label">Kiosk Name</label>
                                            <input type="text" name="kiosk_name" class="form-control" style="text-transform: uppercase;">
                                            <label for="kiosk_type" class="form-label mt-3">Kiosk Type</label>
                                            <input type="text" name="kiosk_type" class="form-control" style="text-transform: uppercase;">
                                            <label for="kiosk_area" class="form-label mt-3">Kiosk Area (sq ft)</label>
                                            <input type="text" name="kiosk_area" class="form-control" style="text-transform: uppercase;">
                                            <div id="superBuildUpError" style="color: red;"></div>

                                            <label for="kiosk_rate" class="form-label mt-3">Kiosk Rate (sq ft)</label>
                                            <input type="text" name="kiosk_rate" class="form-control" style="text-transform: uppercase;">
                                        </div>

                                        <!-- Chair Space Fields -->
                                        <div class="col-md-6 mb-4" id="chairSpaceFields" style="display: {{ $room_type == 'Chair space' ? 'block' : 'none' }};">
                                            <label for="chair_name" class="form-label">Chair Name</label>
                                            <input type="text" name="chair_name" class="form-control" style="text-transform: uppercase;">
                                            <label for="chair_type" class="form-label mt-3">Chair Type</label>
                                            <select name="chair_type" class="form-select" style="text-transform: uppercase;">
                                                <option value="" disabled selected>Select Chair Type</option>
                                                <option value="Executive Chair">Executive Chair</option>
                                                <option value="Recliner Chair">Recliner Chair</option>
                                            </select>
                                            <label for="chair_space_in_sq" class="form-label mt-3">Chair Space (sq ft)</label>
                                            <input type="text" name="chair_space_in_sq" class="form-control" style="text-transform: uppercase;">
                                            <div id="superBuildUpError" style="color: red;"></div>

                                            <label for="chair_rate" class="form-label mt-3">Chair Rate (sq ft)</label>
                                            <input type="text" name="chair_rate" class="form-control" style="text-transform: uppercase;">
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">Add Room</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const roomTypeSelect = document.getElementById('room_type');
        const roomFloorInput = document.getElementById('room_floor');
        const noOfFloors = parseInt(document.getElementById('no_of_floors').value);
        const result = parseFloat(document.getElementById('result').value);
        const result_carpet = parseFloat(document.getElementById('result_carpet').value);
        
        const floorError = document.getElementById('floorError');
        const superBuildUpError = document.getElementById('superBuildUpError');
        const CapertAreaError = document.getElementById('CapertAreaError');
        
        const flatFields = document.getElementById('flatFields');
        const shopsFields = document.getElementById('shopsFields');
        const tableSpaceFields = document.getElementById('tableSpaceFields');
        const kioskFields = document.getElementById('kioskFields');
        const chairSpaceFields = document.getElementById('chairSpaceFields');

        const flatSuperBuildUpPriceInput = document.querySelector('input[name="flat_super_build_up_price"]');
        const flatBuildUpAreaInput = document.querySelector('input[name="flat_build_up_area"]');
        const flatCarpetAreaInput = document.querySelector('input[name="flat_carpet_area"]');

        const shopsBuildUpAreaInput = document.querySelector('input[name="build_up_area"]');
        const shopsCarpetAreaInput = document.querySelector('input[name="carpet_area"]');
        
        const tableSpaceAreaInput = document.querySelector('input[name="space_area"]');
        const tableSpaceRateInput = document.querySelector('input[name="space_rate"]');
        
        const kioskAreaInput = document.querySelector('input[name="kiosk_area"]');
        const kioskRateInput = document.querySelector('input[name="kiosk_rate"]');
        
        const chairSpaceInput = document.querySelector('input[name="chair_space_in_sq"]');
        const chairRateInput = document.querySelector('input[name="chair_rate"]');

        function toggleFields(roomType) {
            flatFields.style.display = roomType === 'Flat' ? 'block' : 'none';
            shopsFields.style.display = roomType === 'Shops' ? 'block' : 'none';
            tableSpaceFields.style.display = roomType === 'Table space' ? 'block' : 'none';
            kioskFields.style.display = roomType === 'Kiosk' ? 'block' : 'none';
            chairSpaceFields.style.display = roomType === 'Chair space' ? 'block' : 'none';
        }

        function validateFloorNumber() {
            const floorValue = parseInt(roomFloorInput.value);
            if (floorValue > noOfFloors) {
                floorError.textContent = `The floor number cannot be greater than ${noOfFloors}.`;
            } else {
                floorError.textContent = '';
            }
        }

        function validateSuperBuildUpArea() {
            const flatBuildUpAreaValue = parseFloat(flatBuildUpAreaInput.value);
            if (!isNaN(flatBuildUpAreaValue) && flatBuildUpAreaValue > result) {
                superBuildUpError.textContent = `Value out of range. It should not exceed ${result}.`;
            } else {
                superBuildUpError.textContent = '';
            }
        }

        function validateCarpetArea() {
            const flatCarpetAreaValue = parseFloat(flatCarpetAreaInput.value);
            if (!isNaN(flatCarpetAreaValue) && flatCarpetAreaValue > result_carpet) {
                CapertAreaError.textContent = `Value out of range. It should not exceed ${result_carpet}.`;
            } else {
                CapertAreaError.textContent = '';
            }
        }

        function validateShopsBuildUpArea() {
            const shopsBuildUpAreaValue = parseFloat(shopsBuildUpAreaInput.value);
            if (!isNaN(shopsBuildUpAreaValue) && shopsBuildUpAreaValue > result) {
                shopsSuperBuildUpError.textContent = `Value out of range. It should not exceed ${result}.`;
            } else {
                shopsSuperBuildUpError.textContent = '';
            }
        }

        function validateShopsCarpetArea() {
        const shopsCarpetAreaValue = parseFloat(shopsCarpetAreaInput.value);
        if (!isNaN(shopsCarpetAreaValue) && shopsCarpetAreaValue > result_carpet) {
            shopsCapertAreaError.textContent = `Value out of range. It should not exceed ${result_carpet}.`;
        } else {
            shopsCapertAreaError.textContent = '';
        }
    }


        function validateShopsFields() {
            const shopsBuildUpAreaValue = parseFloat(shopsBuildUpAreaInput.value);
            const shopsCarpetAreaValue = parseFloat(shopsCarpetAreaInput.value);

            if (!isNaN(shopsBuildUpAreaValue) && shopsBuildUpAreaValue > result) {
                superBuildUpError.textContent = `Value out of range. It should not exceed ${result}.`;
            } else {
                superBuildUpError.textContent = '';
            }

            if (!isNaN(shopsCarpetAreaValue) && shopsCarpetAreaValue > result_carpet) {
                CapertAreaError.textContent = `Value out of range. It should not exceed ${result_carpet}.`;
            } else {
                CapertAreaError.textContent = '';
            }
        }

        function validateTableSpaceFields() {
            const tableSpaceAreaValue = parseFloat(tableSpaceAreaInput.value);
            if (!isNaN(tableSpaceAreaValue) && tableSpaceAreaValue > result) {
                superBuildUpError.textContent = `Value out of range. It should not exceed ${result}.`;
            } else {
                superBuildUpError.textContent = '';
            }
        }

        function validateKioskFields() {
            const kioskAreaValue = parseFloat(kioskAreaInput.value);
            if (!isNaN(kioskAreaValue) && kioskAreaValue > result) {
                superBuildUpError.textContent = `Value out of range. It should not exceed ${result}.`;
            } else {
                superBuildUpError.textContent = '';
            }
        }

        function validateChairSpaceFields() {
            const chairSpaceValue = parseFloat(chairSpaceInput.value);
            if (!isNaN(chairSpaceValue) && chairSpaceValue > result) {
                superBuildUpError.textContent = `Value out of range. It should not exceed ${result}.`;
            } else {
                superBuildUpError.textContent = '';
            }
        }

        function validateFields() {
            validateFloorNumber();
            
            switch (roomTypeSelect.value) {
                case 'Flat':
                    validateSuperBuildUpArea();
                    validateCarpetArea();
                    break;
                case 'Shops':
                    validateShopsFields();
                    break;
                case 'Table space':
                    validateTableSpaceFields();
                    break;
                case 'Kiosk':
                    validateKioskFields();
                    break;
                case 'Chair space':
                    validateChairSpaceFields();
                    break;
                default:
                    break;
            }
        }

        roomTypeSelect.addEventListener('change', function () {
            toggleFields(roomTypeSelect.value);
            validateFields(); // Validate when room type changes
        });

        roomFloorInput.addEventListener('input', validateFloorNumber);

        flatBuildUpAreaInput.addEventListener('input', validateSuperBuildUpArea);
        flatCarpetAreaInput.addEventListener('input', validateCarpetArea);
        
        shopsBuildUpAreaInput.addEventListener('input', validateShopsFields);
        shopsCarpetAreaInput.addEventListener('input', validateShopsFields);
        
        tableSpaceAreaInput.addEventListener('input', validateTableSpaceFields);
        
        kioskAreaInput.addEventListener('input', validateKioskFields);
        
        chairSpaceInput.addEventListener('input', validateChairSpaceFields);

        
        document.getElementById('addRoomForm').addEventListener('submit', function(event) {
        if (floorError.textContent || superBuildUpError.textContent || CapertAreaError.textContent) {
            event.preventDefault();
            alert('Please correct the errors before submitting the form.');
        }else{
            alert('Are you sure you want to create this room?');
        }
        });
       
        toggleFields('{{ $room_type ?? '' }}'); 
        validateFloorNumber(); // Ensure initial validation
        validateFields(); // Ensure initial validation for room type fields
        
    });
</script>
@endsection
