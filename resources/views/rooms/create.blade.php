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
                                                @foreach ($roomTypes as $type)
                                                    <option value="{{ $type->name }}" {{ old('room_type') == $type->name ? 'selected' : '' }}>
                                                        {{ $type->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

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

                                        <div class="col-md-6 mb-4" id="shopsFields" style="display: {{ $room_type == 'Shops' ? 'block' : 'none' }};">
                                            <label for="shop_type" class="form-label">Shop Type</label>
                                            <input type="text" name="shop_type" id="shop_type" class="form-control" style="text-transform: uppercase;">

                                            <label for="build_up_area" class="form-label mt-3">Super Build-up Area (sq ft)</label>
                                            <input type="text" name="build_up_area" id="build_up_area" class="form-control" style="text-transform: uppercase;">
                                            <div id="shopsSuperBuildUpError" style="color: red;"></div>

                                            <label for="super_build_up_price" class="form-label mt-3">Super Build up Area Rate</label>
                                            <input type="text" name="super_build_up_price" id="super_build_up_price" class="form-control" style="text-transform: uppercase;">

                                            <label for="carpet_area" class="form-label mt-3">Shop Carpet Area (sq ft)</label>
                                            <input type="text" name="carpet_area" id="carpet_area" class="form-control" style="text-transform: uppercase;">
                                            <div id="shopsCapertAreaError" style="color: red;"></div>

                                            <label for="carpet_area_price" class="form-label mt-3">Shop Carpet Area Rate</label>
                                            <input type="text" name="carpet_area_price" id="carpet_area_price" class="form-control" style="text-transform: uppercase;">
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
                                        <div id="tableSpaceError" style="color: red;"></div> <!-- Ensure this ID matches -->
                                    
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
                                            <div id="kioskError" style="color: red;"></div>
                                        
                                            <label for="kiosk_rate" class="form-label mt-3">Kiosk Rate (sq ft)</label>
                                            <input type="text" name="kiosk_rate" class="form-control" style="text-transform: uppercase;">
                                        </div>

                                        
                                       <!-- Chair Space fields -->
                                        <div class="col-md-6 mb-4" id="chairSpaceFields" style="display: none;">
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
                                            <div id="chairSpaceError" style="color: red;"></div>

                                            <label for="chair_rate" class="form-label mt-3">Chair Rate (sq ft)</label>
                                            <input type="text" name="chair_rate" class="form-control" style="text-transform: uppercase;">
                                        </div>
                                        <div class="col-md-6 mb-4" id="customFields" style="display: {{ !in_array($room_type, ['Flat', 'Shops', 'Table space', 'Kiosk', 'Chair space']) ? 'block' : 'none' }};">
                                            <label for="custom_name" class="form-label"> Room Type Name</label>
                                            <input type="text" name="custom_name" class="form-control" style="text-transform: uppercase;">
                                        
                                            <label for="custom_type" class="form-label mt-3"> Type</label>
                                            <input type="text" name="custom_type" class="form-control" style="text-transform: uppercase;">
                                        
                                            <label for="custom_area" class="form-label mt-3"> Area (sq ft)</label>
                                            <input type="text" name="custom_area" class="form-control" style="text-transform: uppercase;">
                                        
                                            <label for="custom_rate" class="form-label mt-3"> Rate (sq ft)</label>
                                            <input type="text" name="custom_rate" class="form-control" style="text-transform: uppercase;">
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

        const errors = {
            floor: document.getElementById('floorError'),
            superBuildUp: document.getElementById('superBuildUpError'),
            carpet: document.getElementById('CapertAreaError'),
            shopsSuperBuildUp: document.getElementById('shopsSuperBuildUpError'),
            shopsCarpet: document.getElementById('shopsCapertAreaError'),
            tableSpace: document.getElementById('tableSpaceError'),
            kiosk: document.getElementById('kioskError'),
            chairSpace: document.getElementById('chairSpaceError'),
        };

        const fields = {
            flat: document.getElementById('flatFields'),
            shops: document.getElementById('shopsFields'),
            tableSpace: document.getElementById('tableSpaceFields'),
            kiosk: document.getElementById('kioskFields'),
            chairSpace: document.getElementById('chairSpaceFields'),
        };

        const inputValues = {
            flat: {
                superBuildUp: document.querySelector('input[name="flat_super_build_up_area"]'),
                carpet: document.querySelector('input[name="flat_carpet_area"]'),
            },
            shops: {
                buildUp: document.querySelector('input[name="build_up_area"]'),
                carpet: document.querySelector('input[name="carpet_area"]'),
            },
            tableSpace: {
                area: document.querySelector('input[name="space_area"]'),
            },
            kiosk: {
                area: document.querySelector('input[name="kiosk_area"]'),
            },
            chairSpace: {
                area: document.querySelector('input[name="chair_space_in_sq"]'),
            },
        };

        function toggleFields(roomType) {
            Object.values(fields).forEach(field => {
                field.style.display = field.id === roomType + 'Fields' ? 'block' : 'none';
            });
        }

        function validateField(value, limit, errorElement) {
            if (!isNaN(value) && value > limit) {
                errorElement.textContent = `Value out of range. It should not exceed ${limit}.`;
            } else {
                errorElement.textContent = '';
            }
        }

        function validateFields() {
            const floorValue = parseInt(roomFloorInput.value);
            errors.floor.textContent = floorValue > noOfFloors ? `The floor number cannot be greater than ${noOfFloors}.` : '';

            switch (roomTypeSelect.value) {
                case 'Flat':
                    validateField(parseFloat(inputValues.flat.superBuildUp.value), result, errors.superBuildUp);
                    validateField(parseFloat(inputValues.flat.carpet.value), result_carpet, errors.carpet);
                    break;
                case 'Shops':
                    validateField(parseFloat(inputValues.shops.buildUp.value), result, errors.shopsSuperBuildUp);
                    validateField(parseFloat(inputValues.shops.carpet.value), result_carpet, errors.shopsCarpet);
                    break;
                case 'Table space':
                    validateField(parseFloat(inputValues.tableSpace.area.value), result, errors.tableSpace);
                    break;
                case 'Kiosk':
                    validateField(parseFloat(inputValues.kiosk.area.value), result, errors.kiosk);
                    break;
                case 'Chair space':
                    validateField(parseFloat(inputValues.chairSpace.area.value), result, errors.chairSpace);
                    break;
                default:
                    break;
            }
        }

        roomTypeSelect.addEventListener('change', function () {
            toggleFields(this.value);
            validateFields();
        });

        roomFloorInput.addEventListener('input', validateFields);

        Object.values(inputValues).forEach(room => {
            Object.values(room).forEach(input => {
                input.addEventListener('input', validateFields);
            });
        });

        document.getElementById('addRoomForm').addEventListener('submit', function(event) {
            if (Object.values(errors).some(error => error.textContent)) {
                event.preventDefault();
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roomTypeSelect = document.querySelector('select[name="room_type"]');
        const predefinedFields = document.querySelectorAll('#flatFields, #shopsFields, #tableSpaceFields, #kioskFields, #chairSpaceFields');
        const customFields = document.querySelector('#customFields');
        
        roomTypeSelect.addEventListener('change', function() {
            const selectedRoomType = this.value;
    
            // Hide predefined fields and show custom fields if the selected type is not in the predefined list
            let isCustomType = true;
            predefinedFields.forEach(field => {
                const shouldShow = field.id === (selectedRoomType.toLowerCase().replace(' ', '') + 'Fields');
                field.style.display = shouldShow ? 'block' : 'none';
                if (shouldShow) isCustomType = false;
            });
    
            customFields.style.display = isCustomType ? 'block' : 'none';
        });
    });
    </script>
    <script>
   document.addEventListener('DOMContentLoaded', function () {
    const roomTypeSelect = document.getElementById('room_type');
    const chairSpaceFields = document.getElementById('chairSpaceFields');
    const tableSpaceFields = document.getElementById('tableSpaceFields');
    const customFields = document.getElementById('customFields');

    function toggleFields() {
        const roomType = roomTypeSelect.value;

        // Show Chair Space fields if 'Chair space' is selected
        if (roomType === 'Chair space') {
            chairSpaceFields.style.display = 'block';
            tableSpaceFields.style.display = 'none';
            customFields.style.display = 'none';
        }
        // Show Table Space fields if 'Table space' is selected
        else if (roomType === 'Table space') {
            tableSpaceFields.style.display = 'block';
            chairSpaceFields.style.display = 'none';
            customFields.style.display = 'none';
        }
        // Show custom fields for non-standard room types
        else if (!['Flat', 'Shops', 'Table space', 'Kiosk', 'Chair space'].includes(roomType)) {
            customFields.style.display = 'block';
            chairSpaceFields.style.display = 'none';
            tableSpaceFields.style.display = 'none';
        }
        // Hide all fields for other room types
        else {
            chairSpaceFields.style.display = 'none';
            tableSpaceFields.style.display = 'none';
            customFields.style.display = 'none';
        }
    }

    // Call toggleFields when the page loads to ensure the correct initial state
    toggleFields();

    // Add event listener for room_type change
    roomTypeSelect.addEventListener('change', toggleFields);
});

    </script>
    
@endsection