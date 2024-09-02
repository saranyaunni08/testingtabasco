@extends('layouts.default', ['title' => 'Add New Building', 'page' => 'building'])

@section('content')
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12">
      <div class="card my-4">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
          <div class="bg-gradient-info shadow-info border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
            <h6 class="text-white text-capitalize ps-3">Add Building</h6>
          </div>
        </div>
        <div class="card-body px-0 pb-2">
          <div class="row p-4">
            <form action="/admin/buildingstore" method="POST">
              @csrf
              <div class="row justify-content-center">
                <div class="col-lg-8">
                  <div class="row">
                    <!-- Existing form fields -->
                    <div class="col-md-6">
                      <div class="form-group mb-4">
                        <label class="form-label">Building Name</label>
                        <input name="building_name" type="text" class="form-control" style="text-transform: capitalize" required>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group mb-4">
                        <label class="form-label">Number of Floors</label>
                        <input name="no_of_floors" type="text" class="form-control" required>
                      </div>
                    </div>

                    <div class="col-md-12">
                      <div class="form-group mb-4">
                        <label class="form-label">Address</label>
                        <input name="building_address" type="text" class="form-control" style="text-transform: capitalize" required>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group mb-4">
                        <label class="form-label">Street</label>
                        <input name="street" type="text" class="form-control" required>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group mb-4">
                        <label class="form-label">City</label>
                        <input name="city" type="text" class="form-control" required>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group mb-4">
                        <label class="form-label">Pin Code</label>
                        <input name="pin_code" type="text" class="form-control" pattern="[0-9]*" required>
                      </div>
                    </div>
                    

                    <div class="col-md-6">
                      <div class="form-group mb-4">
                        <label class="form-label">State</label>
                        <input name="state" type="text" class="form-control" required>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group mb-4">
                        <label class="form-label">Country</label>
                        <input name="country" type="text" class="form-control" required>
                      </div>
                    </div>
                    
                    <div class="col-md-6">
                      <div class="form-group mb-4">
                        <label class="form-label">Super Buildup Area</label>
                        <input name="super_built_up_area" type="text" class="form-control" required>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group mb-4">
                        <label class="form-label">Carpet Area</label>
                        <input name="carpet_area" type="text" class="form-control" required>
                      </div>
                    </div>

                    <div class="col-md-12">
                      <div class="form-group">
                        <label class="form-label">Number of Amenities</label>
                        <input type="number" id="amenitiesCount" class="form-control" min="1" onchange="generateAmenitiesFields(this.value)">
                      </div>
                    </div>
                    
                    <div id="amenitiesFields" class="col-md-12">
                      <!-- Amenities fields will be generated here -->
                    </div>
                    

                    
                  </div>
                </div>
              </div>
              <div class="text-center"> 
                <button type="submit" class="btn btn-info">Submit</button>
                <button type="button" class="btn btn-secondary" onclick="window.history.back();">Cancel</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function generateAmenitiesFields(count) {
    const amenitiesFields = document.getElementById('amenitiesFields');
    amenitiesFields.innerHTML = '';

    for (let i = 1; i <= count; i++) {
      const field = `
        <div class="form-group mb-2">
          <label for="amenityName${i}">Amenity ${i} Name</label>
          <input type="text" id="amenityName${i}" name="amenities[${i}][name]" class="form-control" placeholder="Amenity Name" required>

          <label for="amenityType${i}" class="mt-2">Amenity ${i} Type</label>
          <input type="text" id="amenityType${i}" name="amenities[${i}][type]" class="form-control" placeholder="Amenity Type (e.g., Underground, Multi-equipped Gymnasium)" required>
        </div>
      `;
      amenitiesFields.insertAdjacentHTML('beforeend', field);
    }
  }
</script>

@endsection
