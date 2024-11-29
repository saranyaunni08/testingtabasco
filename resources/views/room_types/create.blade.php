@extends('layouts.default')
<style>
body {
    background: linear-gradient(135deg, #e3f2fd, #90caf9); /* Light blue gradient */
    /* min-height: 100vh; /* Ensure full-screen coverage */
    /* display: flex; */
    /* align-items: center; */
    /* justify-content: center; */ */
}
</style>
@section('content')
<div class="container mt-5">
    <div class="card shadow-lg p-4 rounded">
        <h2 class="text-center mb-4">Add Room Type</h2>

        <!-- Display success message if the room type is added -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Display validation errors -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="fas fa-exclamation-triangle"></i> Please correct the errors:</strong>
                <ul class="mt-2 mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Form to add a new room type -->
        <form action="{{ route('admin.room_types.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label fw-bold"><i class="fas fa-bed"></i> Room Type Name</label>
                <input type="text" name="name" class="form-control" id="name" placeholder="Enter room type" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-plus-circle"></i> Add Room Type</button>
            </div>
        </form>

        <!-- Link to go back to the room types listing page -->
        <div class="mt-4 text-center">
            <a href="{{ route('admin.room_types.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-right"></i> Back to Room Types</a>
        </div>
    </div>
</div>

<!-- Add Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection
