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
          <form action="{{ route('admin.rooms.store') }}" method="POST">
            @csrf
            <div class="row justify-content-center">
              <div class="col-lg-8">
                <div class="row">
                  <div class="col-md-6">
                    <div class="input-group input-group-dynamic mb-4">
                      <label for="room_number" class="form-label">Room Number</label>
                      <input type="text" name="room_number" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="input-group input-group-dynamic mb-4">
                        <select name="building_id" class="form-select">
                            <option value="" disabled selected>Select Building</option>
                            @foreach ($buildings as $building)
                                <option value="{{ $building->id }}">{{ $building->building_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                

                  <div class="col-md-6">
                    <div class="input-group input-group-dynamic mb-4">
                      <label for="room_floor" class="form-label">Floor</label>
                      <input type="text" name="room_floor" class="form-control">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="input-group input-group-dynamic mb-4">
                      <select name="room_type" class="form-select">
                        <option value="" disabled selected>Select Room Type</option>
                        <option value="Flat">Flat</option>
                        <option value="Shops">Shops</option>
                        <option value="Car parking">Car parking</option>
                        <option value="Table space">Table space</option>
                        <option value="Chair space">Chair space</option>
                        <option value="Kiosk">Kiosk</option>
                        <!-- Add more room types as needed -->
                      </select>
                    </div>
                  </div>

                  <div class="col-md-6" id="flatFields" style="display: none;">
                    <div class="input-group input-group-dynamic mb-4">
                      <label for="build_up_area" class="form-label">Build-Up Area (sq ft)</label>
                      <input type="text" name="build_up_area" class="form-control">
                    </div>
                    <div class="input-group input-group-dynamic mb-4">
                      <label for="carpet_area" class="form-label">Carpet Area (sq ft)</label>
                      <input type="text" name="carpet_area" class="form-control">
                    </div>
                    <div class="input-group input-group-dynamic mb-4">
                      <label for="flat_rate" class="form-label">Flat Rate</label>
                      <input type="text" name="flat_rate" class="form-control">
                    </div>
                    <div class="input-group input-group-dynamic mb-4">
                      <label for="super_build_up_price" class="form-label">Super Build-Up Area Price</label>
                      <input type="text" name="super_build_up_price" class="form-control">
                    </div>
                    <div class="input-group input-group-dynamic mb-4">
                      <label for="carpet_area_price" class="form-label">Carpet Area Price</label>
                      <input type="text" name="carpet_area_price" class="form-control">
                    </div>
                  </div>

                  <div class="col-md-6" id="shopsFields" style="display: none;">
                    <div class="input-group input-group-dynamic mb-4">
                        <label for="shop_number" class="form-label">Shop Number</label>
                        <input type="text" name="shop_number" class="form-control">
                    </div>
                    <div class="input-group input-group-dynamic mb-4">
                        <select name="shop_type" class="form-select">
                            <option value="" disabled selected>Select Shop Type</option>
                            <option value="Retail">Retail Shop</option>
                            <option value="Restaurant">Restaurant</option>
                            <option value="Office">Office</option>
                            <!-- Add more shop types as needed -->
                        </select>
                    </div>
                    <div class="input-group input-group-dynamic mb-4">
                        <label for="shop_area" class="form-label">Shop Area (sq ft)</label>
                        <input type="text" name="shop_area" class="form-control">
                    </div>
                    <div class="input-group input-group-dynamic mb-4">
                        <label for="shop_rate" class="form-label">Shop Rate</label>
                        <input type="text" name="shop_rate" class="form-control">
                    </div>
                    <div class="input-group input-group-dynamic mb-4">
                        <label for="shop_rental_period" class="form-label">Rental Period</label>
                        <input type="text" name="shop_rental_period" class="form-control">
                    </div>
                </div>
                  

                  <div class="col-md-6" id="carParkingFields" style="display: none;">
                    <div class="input-group input-group-dynamic mb-4">
                      <label for="parking_number" class="form-label">Parking Number</label>
                      <input type="text" name="parking_number" class="form-control">
                    </div>
                    <div class="input-group input-group-dynamic mb-4">
                      <select name="parking_type" class="form-select">
                        <option value="" disabled selected>Select Parking Type</option>
                        <option value="Covered">Covered Parking</option>
                        <option value="Open">Open Parking</option>
                        <option value="Underground">Underground Parking</option>
                      </select>
                    </div>
                    <div class="input-group input-group-dynamic mb-4">
                      <label for="parking_area" class="form-label">Parking Area (sq ft)</label>
                      <input type="text" name="parking_area" class="form-control">
                    </div>
                    <div class="input-group input-group-dynamic mb-4">
                      <label for="parking_rate" class="form-label">Parking Rate</label>
                      <input type="text" name="parking_rate" class="form-control">
                    </div>
                  </div>


                  <div class="col-md-6" id="tableSpaceFields" style="display: none;">
                    <div class="input-group input-group-dynamic mb-4">
                        <label for="space_name" class="form-label">Space Name</label>
                        <input type="text" name="space_name" class="form-control">
                    </div>
                    <div class="input-group input-group-dynamic mb-4">
                        <select name="space_type" class="form-select">
                            <option value="" disabled selected>Select Space Type</option>
                            <option value="Massage Chair">Massage Chair Space</option>
                            <option value="Table Space">Table Space</option>
                            <!-- Add more space types as needed -->
                        </select>
                    </div>
                    <div class="input-group input-group-dynamic mb-4">
                        <label for="space_area" class="form-label">Space Area (sq ft)</label>
                        <input type="text" name="space_area" class="form-control">
                    </div>
                    <div class="input-group input-group-dynamic mb-4">
                        <label for="space_rate" class="form-label">Space Rate</label>
                        <input type="text" name="space_rate" class="form-control">
                    </div>
                </div>
                
                <div class="col-md-6" id="kioskFields" style="display: none;">
                  <div class="input-group input-group-dynamic mb-4">
                      <label for="kiosk_name" class="form-label">Kiosk Name</label>
                      <input type="text" name="kiosk_name" class="form-control">
                  </div>
                  <div class="input-group input-group-dynamic mb-4">

                    <select name="kiosk_type" class="form-select">
                          <option value="" disabled selected>Select Kiosk Type</option>
                          <option value="Food Kiosk">Food Kiosk</option>
                          <option value="Retail Kiosk">Retail Kiosk</option>
                          <option value="Information Kiosk">Information Kiosk</option>
                          <!-- Add more kiosk types as needed -->
                      </select>
                  </div>
                  <div class="input-group input-group-dynamic mb-4">
                      <label for="kiosk_area" class="form-label">Kiosk Area (sq ft)</label>
                      <input type="text" name="kiosk_area" class="form-control">
                  </div>
                  <div class="input-group input-group-dynamic mb-4">
                      <label for="kiosk_rate" class="form-label">Kiosk Rate</label>
                      <input type="text" name="kiosk_rate" class="form-control">
                  </div>
              </div>

              <div class="col-md-6" id="chairSpaceFields" style="display: none;">
                <div class="input-group input-group-dynamic mb-4">
                    <label for="chair_name" class="form-label">Chair Name</label>
                    <input type="text" name="chair_name" class="form-control">
                </div>
                <div class="input-group input-group-dynamic mb-4">
                    <select name="chair_type" class="form-select">
                        <option value="" disabled selected>Select Chair Type</option>
                        <option value="Executive Chair">Executive Chair</option>
                        <option value="Recliner Chair">Recliner Chair</option>
                        <!-- Add more chair types as needed -->
                    </select>
                </div>
                <div class="input-group input-group-dynamic mb-4">
                    <label for="chair_material" class="form-label">Chair Material</label>
                    <input type="text" name="chair_material" class="form-control">
                </div>
                <div class="input-group input-group-dynamic mb-4">
                    <label for="chair_price" class="form-label">Chair Price</label>
                    <input type="text" name="chair_price" class="form-control">
                </div>
              </div>
              
              <div class="text-center">
                    <button type="submit" class="btn btn-primary">Add</button>
                    <button type="button" class="btn btn-secondary" onclick="window.history.back();">Cancel</button>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Include Bootstrap JS and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Hide all specific fields initially
  document.getElementById('flatFields').style.display = 'none';
  document.getElementById('shopsFields').style.display = 'none';
  document.getElementById('carParkingFields').style.display = 'none';
  document.getElementById('tableSpaceFields').style.display = 'none';
  document.getElementById('kioskFields').style.display = 'none';
  document.getElementById('chairSpaceFields').style.display = 'none';
  
  // Add change event listener to room type select
  document.querySelector('select[name="room_type"]').addEventListener('change', function() {
    // Hide all specific fields first
    document.getElementById('flatFields').style.display = 'none';
    document.getElementById('shopsFields').style.display = 'none';
    document.getElementById('carParkingFields').style.display = 'none';
    document.getElementById('tableSpaceFields').style.display = 'none';
    document.getElementById('kioskFields').style.display = 'none';
    document.getElementById('chairSpaceFields').style.display = 'none';
    
    // Get the selected room type
    const selectedRoomType = this.value;
    // Show the specific fields based on the selected room type
    if (selectedRoomType === 'Flat') {
      document.getElementById('flatFields').style.display = 'block';
    } else if (selectedRoomType === 'Shops') {
      document.getElementById('shopsFields').style.display = 'block';
    } else if (selectedRoomType === 'Car parking') {
      document.getElementById('carParkingFields').style.display = 'block';
    } else if (selectedRoomType === 'Table space') {
      document.getElementById('tableSpaceFields').style.display = 'block';
    } else if (selectedRoomType === 'Chair space') {
      document.getElementById('chairSpaceFields').style.display = 'block';
    } else if (selectedRoomType === 'Kiosk') {
      document.getElementById('kioskFields').style.display = 'block';
    }
    // Add conditions for other room types here
  });
});
</script>

@endsection