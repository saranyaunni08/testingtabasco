@extends('layouts.default', ['title' => 'Dashboard', 'page' => 'dashboard'])

@section('content')
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-lg-8 mx-auto">
      <div class="card shadow-lg">
        <div class="card-header bg-gradient-info py-3">
          <h6 class="text-white text-uppercase mb-0">Add Room</h6>
        </div>
        <div class="card-body">
          <form action="{{ route('rooms.store') }}" method="POST">
            @csrf
            <div class="mb-3">
              <label for="room_number" class="form-label">Room Number</label>
              <input type="text" name="room_number" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="room_type" class="form-label">Room Type</label>
              <input type="text" name="room_type" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="square_footage" class="form-label">Square Footage</label>
              <input type="text" name="square_footage" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="rate_per_square_foot" class="form-label">Rate Per Square Foot</label>
              <input type="text" name="rate_per_square_foot" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="selling_price" class="form-label">Selling Price</label>
              <input type="text" name="selling_price" class="form-control" required>
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




