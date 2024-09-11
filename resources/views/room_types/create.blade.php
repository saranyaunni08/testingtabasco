@extends('layouts.default') <!-- Extend your default layout -->

@section('content')
<div class="container mt-5">
    <h2>Add Room Type</h2>

    <!-- Display success message if the room type is added -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Display validation errors -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form to add a new room type -->
    <form action="{{ route('admin.room_types.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Room Type Name</label>
            <input type="text" name="name" class="form-control" id="name" placeholder="Enter room type" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Room Type</button>
    </form>

    <!-- Link to go back to the room types listing page -->
    <div class="mt-3">
        <a href="{{ route('admin.room_types.index') }}" class="btn btn-secondary">Back to Room Types</a>
    </div>
</div>
@endsection
