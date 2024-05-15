<!-- resources/views/rooms/edit.blade.php -->
@extends('layouts.default', ['title' => 'Edit Room', 'page' => 'Room'])

@section('content')
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12">
      <div class="card my-4">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
          <div class="bg-gradient-info shadow-info border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
            <h6 class="text-white text-capitalize ps-3">Edit Building</h6>
          </div>
        </div>

    <div class="card-body px-0 pb-2">
        <div class="row p-4">
            <form action="{{ route('admin.rooms.update', $room->id) }}" method="POST">
    <div class="container">
        <h1>Edit Room</h1>
        <form action="{{ route('admin.rooms.update', $room->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row justify-content-center">
                <div class="col-lg-8">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="input-group input-group-dynamic mb-4">
                      <label for="room_number">Room Number</label><br><br>
                      <input type="text" name="room_number" class="form-control" id="room_number" value="{{ $room->room_number }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group input-group-dynamic mb-4">
                <label for="room_floor">Floor</label><br><br>
                <input type="text" name="room_floor" class="form-control" id="room_floor" value="{{ $room->room_floor }}">
            </div>
            </div>
            <div class="col-md-6">
                <div class="input-group input-group-dynamic mb-4">
                <label for="room_type">Type</label><br><br>
                <input type="text" name="room_type" class="form-control" id="room_type" value="{{ $room->room_type }}">
            </div>
            </div>
            <div class="col-md-6">
                <div class="input-group input-group-dynamic mb-4">
                <label for="build_up_area">Built up Area</label><br><br>
                <input type="text" name="build_up_area" class="form-control" id="build_up_area" value="{{ $room->build_up_area }}">
            </div>
            </div>
            <div class="col-md-6">
                <div class="input-group input-group-dynamic mb-4">
                 <label for="carpet_area">Carpet Area</label><br><br>
                <input type="text" name="carpet_area" class="form-control" id="carpet_area" value="{{ $room->carpet_area }}">
            </div>
            </div>
            <div class="col-md-6">
                <div class="input-group input-group-dynamic mb-4">    
                <label for="flat_rate">Flat Rate</label>
                <input type="text" name="flat_rate" class="form-control" id="flat_rate" value="{{ $room->flat_rate }}">
            </div>
            </div>
            <div class="col-md-6">
                <div class="input-group input-group-dynamic mb-4">    
                <label for="super_build_up_price">Super Build Up Price</label><br><br>
                <input type="text" name="super_build_up_price" class="form-control" id="super_build_up_price" value="{{ $room->super_build_up_price }}">
            </div>
            </div>
            <div class="col-md-6">
                <div class="input-group input-group-dynamic mb-4">    
                <label for="carpet_area_price">Carpet Area Price</label><br><br>
                <input type="text" name="carpet_area_price" class="form-control" id="carpet_area_price" value="{{ $room->carpet_area_price }}">
            </div>
            </div>
            <div class="col-md-12">
                <div class="text-center">
            <button type="submit" class="btn btn-primary">Update Room</button>
        </div>
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
</div>
@endsection
