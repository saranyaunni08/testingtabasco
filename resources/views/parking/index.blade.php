@extends('layouts.default')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 font-weight-bold">Parking Slots</h1>
            <a href="{{ route('admin.parking.create') }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus-circle"></i> Add New Parking Slot
            </a>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <table class="table table-hover table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>Slot Number</th>
                            <th>Floor Number</th>
                            <th>Status</th>
                            <th>Purchaser Name</th>
                            <th>Amount</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($parkings as $parking)
                            <tr class="{{ $parking->status === 'available' ? 'table-success' : 'table-warning' }}">
                                <td>{{ $parking->slot_number }}</td>
                                <td>{{ $parking->floor_number }}</td>
                                <td>
                                    <span class="badge badge-{{ $parking->status === 'available' ? 'success' : 'danger' }}">
                                        {{ ucfirst($parking->status) }}
                                    </span>
                                </td>
                                <td>{{ $parking->purchaser_name ?? 'N/A' }}</td>
                                <td>₹{{ number_format($parking->amount, 2) }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.parking.edit', $parking->id) }}" class="btn btn-sm btn-warning mr-1">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.parking.destroy', $parking->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this parking slot?');">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
