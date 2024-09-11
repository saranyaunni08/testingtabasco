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
                </tr>
            </thead>
            <tbody>
                @foreach($roomTypes as $roomType)
                    <tr>
                        <td>{{ $roomType->id }}</td>
                        <td>{{ $roomType->name }}</td>
                        <td>{{ $roomType->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Link to add a new room type -->
        <a href="{{ route('admin.room_types.create') }}" class="btn btn-primary">Add New Room Type</a>
    </div>
@endsection
