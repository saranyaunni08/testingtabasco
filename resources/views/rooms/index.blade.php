@extends('layouts.default', ['title' => 'Dashboard', 'page' => 'dashboard'])

@section('content')
<div class="container-fluid py-4">
    <div class="row">
      <div class="col-12">
        <div class="card my-4">
          <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="bg-gradient-info shadow-info border-radius-lg p-3">
              <div class="d-flex justify-content-between align-items-center">
                <h6 class="text-white text-capitalize ps-3 mb-0">Buildings</h6>
                <div class="pe-3">
                    <a href="{{ route('rooms.create') }}" class="btn btn-primary mb-3">Add Room</a>
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
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Sl.no</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Room Number</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Room Type</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Square Footage</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Rate Per Square Footage</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Selling Price</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($rooms as $key => $room)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $room->room_number }}</td>
                            <td>{{ $room->room_type }}</td>
                            <td>{{ $room->square_footage }}</td>
                            <td>{{ $room->rate_per_square_foot }}</td>
                            <td>{{ $room->selling_price }}</td>
                            <td>
                                <form action="{{ route('rooms.destroy', $room->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this room?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                
                        </table>
                    {{-- @else
                        <p>No rooms available.</p>
                    @endif --}}
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@section('sidebar')
@parent {{-- Include parent content from the default layout --}}
{{-- Add room module link in the sidebar --}}
<li class="nav-item">
  <a class="nav-link" href="{{ route('rooms.create') }}">
    <i class="fas fa-plus"></i>
    <span class="nav-link-text ms-1">Add Room</span>
  </a>
</li>
@endsection




