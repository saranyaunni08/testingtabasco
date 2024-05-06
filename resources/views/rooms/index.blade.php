@extends('layouts.default', ['title' => 'Dashboard', 'page' => 'dashboard'])

@section('content')
<div class="container-fluid py-7">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Rooms</div>

                <div class="card-body">
                    <a href="{{ route('rooms.create') }}" class="btn btn-primary mb-3">Add Room</a>

                    @if ($rooms->count())
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Room Number</th>
                                    <th>Room Type</th>
                                    <th>Square Footage</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rooms as $room)
                                    <tr>
                                        <td>{{ $room->room_number }}</td>
                                        <td>{{ $room->room_type }}</td>
                                        <td>{{ $room->square_footage }}</td>
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
                    @else
                        <p>No rooms available.</p>
                    @endif
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




