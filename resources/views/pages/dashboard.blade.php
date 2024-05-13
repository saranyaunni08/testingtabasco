@extends('layouts.default', ['title' => 'Dashboard', 'page' => 'dashboard'])
@section('content')
  <div class="container-fluid py-4">
    <div class="row">
      <div class="col-12">
        <div class="card my-4">
          <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="bg-gradient-info shadow-info border-radius-lg pt-4 pb-3">
              <h6 class="text-white text-capitalize ps-3">Dashboard</h6>
            </div>
          </div>
          <div class="card-body px-0 pb-2">
            <div class="row p-4">
              {{-- Your content here --}}
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

@section('sidebar')
  @parent {{-- Include parent content from the default layout --}}
  {{-- Add room module link in the sidebar --}}
  <li class="nav-item">
    <a class="nav-link" href="{{ route('rooms.index') }}"> <!-- Assuming 'rooms.index' is your route for the rooms index -->
      <i class="fas fa-list"></i> <!-- You can use an appropriate icon here -->
      <span class="nav-link-text ms-1">Rooms List</span>
    </a>
  </li>
@endsection