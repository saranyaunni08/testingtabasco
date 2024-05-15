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
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Built up Area</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Carpet Area</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Flate area</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Super build up price</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Carpet Area price</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($flats as $flat)
                                    <tr>
                                        <td>
                                            <h6 class="mb-0 text-xs">{{ $flat->room_number }}</h6>
                                        </td>
                                        <td>
                                            <p class="text-xs text-dark font-weight-normal mb-0">{{ $flat->room_floor }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs text-dark font-weight-normal mb-0">{{ $flat->room_type }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs text-dark font-weight-normal mb-0">{{ $flat->build_up_area }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs text-dark font-weight-normal mb-0">{{ $flat->carpet_area }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs text-dark font-weight-normal mb-0">{{ $flat->flat_rate }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs text-dark font-weight-normal mb-0">{{ $flat->super_build_up_price }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs text-dark font-weight-normal mb-0">{{ $flat->carpet_area_price }}</p>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                            <a href="{{ route('admin.rooms.edit', $flat->id) }}" class="btn btn-sm btn-primary me-1">Edit</a>
                                                <form method="POST" action="{{ route('admin.rooms.destroy', $flat->id) }}">
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

     <!-- Shops Table -->
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
                    <a  class="btn btn-sm btn-primary">Edit</a>
                </td>
                
              
                  <!-- Delete Form -->
                  <td>
                      <form action="{{ route('admin.shops.destroy', $shop->id) }}" method="POST" class="d-inline">
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
                <td>
                    <a  class="btn btn-sm btn-primary">Edit</a>
                </td>
                <td>
                    <form method="POST" action="{{ route('admin.rooms.destroy', $parking->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this parking?')">Delete</button>
                    </form> 
                </td>
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
  </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
                </div>
            </div>
        </div>
    </div>
</div>







