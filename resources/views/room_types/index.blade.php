@extends('layouts.default')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #e3f2fd, #bbdefb); /* Light blue gradient */
    }
    
    .card {
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease-in-out;
        background-color: #ffffff;
    }

    .card:hover {
        transform: scale(1.02);
    }

    .table-hover tbody tr:hover {
        background-color: #e3f2fd; /* Light blue hover effect */
    }

    .btn-primary {
        background-color: #2196f3; /* Bootstrap blue */
        border-color: #2196f3;
        transition: background-color 0.3s ease, transform 0.3s;
    }

    .btn-primary:hover {
        background-color: #1976d2; /* Darker blue on hover */
        transform: translateY(-3px);
    }

    .table th {
        background-color: #2196f3; /* Blue header */
        color: #ffffff;
    }

    .table td, .table th {
        text-align: center;
        vertical-align: middle;
    }

    .add-btn-container {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }

    .alert-success {
        background-color: #bbdefb; /* Light blue alert background */
        color: #0d47a1; /* Dark blue text */
        border-color: #90caf9;
    }
</style>

<div class="container mt-5">
    <div class="card p-4">
        <h2 class="text-center mb-4"><i class="fas fa-list"></i> Room Types List</h2>

        <!-- Display success message if there is one -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Room types table -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th><i class="fas fa-hashtag"></i> ID</th>
                        <th><i class="fas fa-bed"></i> Room Type Name</th>
                        <th><i class="fas fa-calendar-alt"></i> Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roomTypes as $roomType)
                        <tr>
                            <td>{{ $roomType->id }}</td>
                            <td>{{ $roomType->name }}</td>
                            <td>{{ $roomType->created_at->format('d-m-Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">No room types found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Link to add a new room type -->
        <div class="add-btn-container">
            <a href="{{ route('admin.room_types.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus-circle"></i> Add New Room Type
            </a>
        </div>
    </div>
</div>

<!-- Font Awesome for Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection
