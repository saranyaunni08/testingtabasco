
@extends('layouts.default', ['title' => 'Dashboard', 'page' => 'dashboard'])

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
              <div class="table-responsive">
                  <table class="table align-items-center mb-0">
                      <thead>
              <tr>
                <th>ID</th>
                <th>Room Number</th>
                <th>Floor</th>
                <th>Type</th>
                <th>Build up area</th>
                <th>carpet area</th>
                <th>flate area</th>
                <th>super build up price</th>
                <th>carpet area price</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($flats as $flat)
              <tr>
                <td>{{ $flat->id }}</td>
                <td>{{ $flat->room_number }}</td>
                <td>{{ $flat->room_floor }}</td>
                <td>{{ $flat->room_type }}</td>
                <td>{{ $flat->build_up_area }}</td>
                <td>{{ $flat->carpet_area }}</td>
                <td>{{ $flat->flat_rate }}</td>
                <td>{{ $flat->super_build_up_price }}</td>
                <td>{{ $flat->carpet_area_price }}</td>
                
                <td>
                  <a href="{{ route('rooms.edit', $flat->id) }}" class="btn btn-sm btn-primary">Edit</a>
                </td>

                <td>
                  <form action="{{ route('rooms.destroy', $flat->id) }}" method="POST" class="d-inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this room?')">Delete</button>
                  </form> 
                </td>
              </tr>
              
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      {{-- <!-- Shops Table -->
      <div class="card my-4">
        <div class="card-header">
          <h5 class="card-title">Shops List</h5>
        </div>
        <div class="card-body">
          <table class="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Shop Number</th>
                <th>Shop Type</th>
                <!-- Add more columns as needed -->
              </tr>
            </thead>
            <tbody>
              @foreach ($shops as $shop)
              <tr>
                  <!-- Display shop information -->
                  <td>{{ $shop->id }}</td>
                  <td>{{ $shop->shop_number }}</td>
                  <td>{{ $shop->shop_type }}</td>
              
                  <!-- Edit Button -->
                  <td>
                      <a href="{{ route('shops.edit', $shop->id) }}" class="btn btn-sm btn-primary">Edit</a>
                  </td>
              
                  <!-- Delete Form -->
                  <td>
                      <form action="{{ route('shops.destroy', $shop->id) }}" method="POST" class="d-inline">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this shop?')">Delete</button>
                      </form> 
                  </td>
              </tr>
              @endforeach
              
            </tbody>
          </table>
        </div>
      </div>


      
      <!-- Car Parking Table -->
      <div class="card my-4">
        <div class="card-header">
          <h5 class="card-title">Car Parking List</h5>
        </div>
        <div class="card-body">
          <table class="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Parking Number</th>
                <th>Parking Type</th>
                <!-- Add more columns as needed -->
              </tr>
            </thead>
            <tbody>
              @foreach ($carParking as $parking)
              <tr>
                <td>{{ $parking->id }}</td>
                <td>{{ $parking->parking_number }}</td>
                <td>{{ $parking->parking_type }}</td>
                <!-- Add more columns as needed -->
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      <div class="card my-4">
        <div class="card-header">
            <h5 class="card-title">Table Spaces List</h5>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Space Name</th>
                        <th>Space Type</th>
                        <!-- Add more columns as needed -->
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tableSpaces as $space)
                    <tr>
                        <td>{{ $space->id }}</td>
                        <td>{{ $space->space_name }}</td>
                        <td>{{ $space->space_type }}</td>
                        <!-- Add more columns as needed -->
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card my-4">
      <div class="card-header">
          <h5 class="card-title">Chair Spaces List</h5>
      </div>
      <div class="card-body">
          <table class="table">
              <thead>
                  <tr>
                      <th>ID</th>
                      <th>Chair Name</th>
                      <th>Chair Type</th>
                      <!-- Add more columns as needed -->
                  </tr>
              </thead>
              <tbody>
                  @foreach ($chairSpaces as $chair)
                  <tr>
                      <td>{{ $chair->id }}</td>
                      <td>{{ $chair->chair_name }}</td>
                      <td>{{ $chair->chair_type }}</td>
                      <!-- Add more columns as needed -->
                  </tr>
                  @endforeach
              </tbody>
          </table>
      </div>
  </div>

  <!-- Kiosk Table -->
  <div class="card my-4">
      <div class="card-header">
          <h5 class="card-title">Kiosk List</h5>
      </div>
      <div class="card-body">
          <table class="table">
              <thead>
                  <tr>
                      <th>ID</th>
                      <th>Kiosk Name</th>
                      <th>Kiosk Type</th>
                      <!-- Add more columns as needed -->
                  </tr>
              </thead>
              <tbody>
                  @foreach ($kiosks as $kiosk)
                  <tr>
                      <td>{{ $kiosk->id }}</td>
                      <td>{{ $kiosk->kiosk_name }}</td>
                      <td>{{ $kiosk->kiosk_type }}</td>
                      <!-- Add more columns as needed -->
                  </tr>
                  @endforeach
              </tbody>
          </table>
      </div>
  </div> --}}

    </div>
  </div>
</div>
@endsection
