<!-- resources/views/rooms/edit.blade.php -->
@extends('layouts.default', ['title' => 'Edit Room'])

@section('content')
    <div class="container">
        <h1>Edit Room</h1>
        <form action="{{ route('admin.rooms.update', $room->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="room_number">Room Number</label>
                <input type="text" name="room_number" id="room_number" value="{{ $room->room_number }}">
            </div>
            <!-- Add more form fields as needed -->
            <button type="submit" class="btn btn-primary">Update Room</button>
        </form>
    </div>
@endsection
