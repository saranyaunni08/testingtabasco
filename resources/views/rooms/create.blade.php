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
                                                <option value="Flat">Flat</option>
                                                <option value="Shops">Shops</option>
                                                <option value="Table space">Table space</option>
                                                <option value="Chair space">Chair space</option>


                                                <option value="Kiosk">Kiosk</option>
                                            </select>
                                        </div>

                                        <!-- Flat Fields -->
                                        <div class="col-md-6 mb-4" id="flatFields" style="display: none;">
                                            <label for="flat_model" class="form-label">Flat Model</label>
                                            <input type="text" name="flat_model" class="form-control" style="text-transform: uppercase;">
                                            <label for="flat_build_up_area" class="form-label mt-3">Super Build-Up Area (sq ft)</label>
                                            <input type="text" name="flat_build_up_area" class="form-control" style="text-transform: uppercase;">
                                            <label for="flat_super_build_up_price" class="form-label mt-3">Build-Up Area Price in sq</label>
                                            <input type="text" name="flat_super_build_up_price" class="form-control" style="text-transform: uppercase;">
                                            <label for="flat_carpet_area" class="form-label mt-3">Carpet Area (sq ft)</label>
                                            <input type="text" name="flat_carpet_area" class="form-control" style="text-transform: uppercase;">
                                            <label for="flat_carpet_area_price" class="form-label mt-3">Carpet Area Price in Sq</label>
                                            <input type="text" name="flat_carpet_area_price" class="form-control" style="text-transform: uppercase;">


                                        </div>

                                        <!-- Shops Fields -->
                                        <div class="col-md-6 mb-4" id="shopsFields" style="display: none;">
                                            <label for="shop_type" class="form-label">Shop Type</label>
                                            <select name="shop_type" class="form-select" style="text-transform: uppercase;">
                                                <option value="" disabled selected>Select Shop Type</option>
                                                <option value="Retail">Retail Shop</option>
                                                <option value="Restaurant">Restaurant</option>
                                                <option value="Office">Office</option>
                                            </select>
                                            <label for="build_up_area" class="form-label mt-3">Super Build-up Area (sq ft)</label>
                                            <input type="text" name="build_up_area" class="form-control" style="text-transform: uppercase;">
                                            <label for="super_build_up_price" class="form-label mt-3">Super Build up Area Rate</label>
                                            <input type="text" name="super_build_up_price" class="form-control" style="text-transform: uppercase;">
                                            <label for="carpet_area" class="form-label mt-3">Shop Carpet Area (sq ft)</label>
                                            <input type="text" name="carpet_area" class="form-control" style="text-transform: uppercase;">
                                            <label for="carpet_area_price" class="form-label mt-3">Shop Carpet Area Rate</label>
                                            <input type="text" name="carpet_area_price" class="form-control" style="text-transform: uppercase;">
                                        </div>

                                        <!-- Table Space Fields -->
                                        <div class="col-md-6 mb-4" id="tableSpaceFields" style="display: none;">
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
                                            <label for="space_rate" class="form-label mt-3">Space Rate</label>
                                            <input type="text" name="space_rate" class="form-control" style="text-transform: uppercase;">
                                        </div>

                                        <!-- Kiosk Fields -->
                                        <div class="col-md-6 mb-4" id="kioskFields" style="display: none;">
                                            <label for="kiosk_name" class="form-label">Kiosk Name</label>
                                            <input type="text" name="kiosk_name" class="form-control" style="text-transform: uppercase;">
                                            <label for="kiosk_type" class="form-label mt-3">Kiosk Type</label>
                                            <select name="kiosk_type" class="form-select" style="text-transform: uppercase;">
                                                <option value="" disabled selected>Select Kiosk Type</option>
                                                <option value="Food Kiosk">Food Kiosk</option>
                                                <option value="Retail Kiosk">Retail Kiosk</option>
                                                <option value="Information Kiosk">Information Kiosk</option>
                                            </select>
                                            <label for="kiosk_area" class="form-label mt-3">Kiosk Area (sq ft)</label>
                                            <input type="text" name="kiosk_area" class="form-control" style="text-transform: uppercase;">
                                            <label for="kiosk_rate" class="form-label mt-3">Kiosk Rate</label>
                                            <input type="text" name="kiosk_rate" class="form-control" style="text-transform: uppercase;">
                                        </div>

                                        <!-- Chair Space Fields -->
                                        <div class="col-md-6 mb-4" id="chairSpaceFields" style="display: none;">
                                            <label for="chair_name" class="form-label">Chair Name</label>
                                            <input type="text" name="chair_name" class="form-control" style="text-transform: uppercase;">
                                            <div class="input-group input-group-dynamic mb-4">
                                                <select name="chair_type" class="form-select" style="text-transform: uppercase;">
                                                    <option value="" disabled selected>Select Chair Type</option>
                                                    <option value="Executive Chair">Executive Chair</option>
                                                    <option value="Recliner Chair">Recliner Chair</option>
                                                    <!-- Add more chair types as needed -->
                                                </select>
                                            </div>
                                            <label for="chair_space_in_sq" class="form-label">Chair space in sq</label>
                                            <input type="text" name="chair_space_in_sq" class="form-control" style="text-transform: uppercase;">

                                            <label for="chair_space_rate" class="form-label">Chair space rate in sq</label>
                                            <input type="text" name="chair_space_rate" class="form-control" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                    <div class="col-12">
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
      const noOfFloors = document.getElementById('no_of_floors').value;
      const floorError = document.getElementById('floorError');

      const flatFields = document.getElementById('flatFields');
      const shopsFields = document.getElementById('shopsFields');
      const tableSpaceFields = document.getElementById('tableSpaceFields');
      const kioskFields = document.getElementById('kioskFields');
      const chairSpaceFields = document.getElementById('chairSpaceFields');

      roomTypeSelect.addEventListener('change', function () {
          const selectedType = roomTypeSelect.value;
          flatFields.style.display = selectedType === 'Flat' ? 'block' : 'none';
          shopsFields.style.display = selectedType === 'Shops' ? 'block' : 'none';
          tableSpaceFields.style.display = selectedType === 'Table space' ? 'block' : 'none';
          kioskFields.style.display = selectedType === 'Kiosk' ? 'block' : 'none';
          chairSpaceFields.style.display = selectedType === 'Chair space' ? 'block' : 'none';
      });

      roomFloorInput.addEventListener('input', function () {
          const floorValue = parseInt(roomFloorInput.value);
          if (floorValue > noOfFloors) {
              floorError.textContent = `The floor number cannot be greater than ${noOfFloors}.`;
          } else {
              floorError.textContent = '';
          }
      });

      document.getElementById('addRoomForm').addEventListener('submit', function (event) {
          const floorValue = parseInt(roomFloorInput.value);
          if (floorValue > noOfFloors) {
              event.preventDefault();
              floorError.textContent = `The floor number cannot be greater than ${noOfFloors}.`;
          }
      });
  });
</script>

@endsection

