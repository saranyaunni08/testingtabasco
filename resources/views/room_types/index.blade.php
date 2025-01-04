@extends('layouts.default') <!-- Extend your default layout -->

@section('content') <!-- Define the section for content -->
    <div class="container mt-5">
        <h2>Room Types List</h2>

        <!-- Display a success message if there is one -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Room types table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Room Type Name</th>
                    <th>Created At</th>
                    <th><i class="fas fa-check-circle"></i> Counter Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roomTypes as $roomType)
                    <tr>
                        <td>{{ $roomType->id }}</td>
                        <td>{{ $roomType->name }}</td>
                        <td>{{ $roomType->created_at }}</td>
                        <td>{{ ucfirst($roomType->counter_status) }}</td> <!-- Capitalize first letter -->
                    </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">No room types found.</td>
                            <td colspan="4" class="text-center">No room types found.</td>
                        </tr>
                    @endforelse
                @endforeach
            </tbody>
        </table>

        <!-- Link to add a new room type -->
        <a href="{{ route('admin.room_types.create') }}" class="btn btn-primary">Add New Room Type</a>
    </div>
@endsection
