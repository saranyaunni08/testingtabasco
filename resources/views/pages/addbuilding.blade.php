@extends('layouts.default', ['title' => 'Dashboard', 'page' => 'building'])

@section('content')

<div class="container-fluid py-4">
  <div class="row">
    <div class="col-lg-8 mx-auto">
      <div class="card shadow-lg">
        <div class="card-header bg-gradient-info py-3">
          <h6 class="text-white text-uppercase mb-0">Add Building</h6>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.addbuilding.store') }}" method="POST">
            @csrf
            <div class="mb-3">
              <label for="room_number" class="form-label">Building Name</label>
              <input type="text" name="building_name" class="form-control" required>
            </div>


            <div class="mb-3">
              <label for="room_type" class="form-label">Building Address</label>
		<textarea id="address" name="building_address" class="form-control" ></textarea>
            </div>

            <div class="mb-3">
              <label for="square_footage" class="form-label">Number of Floors</label>
              <input type="number" id="num_floors" name="no_of_floors" class="form-control" min="1">
            </div>
            <div class="mb-3">
             <label>Amenities:</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="amenity1" name="building_amenities[]" value="Swimming Pool">
                                <label class="form-check-label" for="amenity1">Swimming Pool</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="amenity2" name="building_amenities[]" value="Gym">
                                <label class="form-check-label" for="amenity2">Gym</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="amenity3" name="building_amenities[]" value="Parking">
                                <label class="form-check-label" for="amenity3">Parking</label>

            </div>
            
            <div class="text-center">
              <button type="submit" class="btn btn-primary">Add</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection