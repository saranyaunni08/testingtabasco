@extends('layouts.default')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Edit Parking Slot</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.parking.update', $parking->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-group">
                                <label for="slot_number" class="font-weight-bold">Slot Number</label>
                                <input type="text" class="form-control" id="slot_number" name="slot_number" value="{{ $parking->slot_number }}" placeholder="Enter slot number" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="floor_number" class="font-weight-bold">Floor Number</label>
                                <input type="number" class="form-control" id="floor_number" name="floor_number" value="{{ $parking->floor_number }}" placeholder="Enter floor number" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="status" class="font-weight-bold">Status</label>
                                <select class="form-control" id="status" name="status" required onchange="togglePurchaserName()">
                                    <option value="available" {{ $parking->status == 'available' ? 'selected' : '' }}>Available</option>
                                    <option value="occupied" {{ $parking->status == 'occupied' ? 'selected' : '' }}>Occupied</option>
                                </select>
                            </div>
                            
                            <div class="form-group" id="purchaserNameField" style="{{ $parking->status == 'occupied' ? 'display: block;' : 'display: none;' }}">
                                <label for="purchaser_name" class="font-weight-bold">Purchaser Name</label>
                                <input type="text" class="form-control" id="purchaser_name" name="purchaser_name" value="{{ $parking->purchaser_name }}" placeholder="Enter purchaser name">
                            </div>
                            
                            <div class="form-group">
                                <label for="amount" class="font-weight-bold">Amount</label>
                                <input type="number" step="0.01" class="form-control" id="amount" name="amount" value="{{ $parking->amount }}" placeholder="Enter amount" required>
                            </div>
                            
                            <div class="text-center">
                                <button type="submit" class="btn btn-success px-4 py-2 mt-3">
                                    <i class="fas fa-save"></i> Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePurchaserName() {
            const status = document.getElementById('status').value;
            const purchaserNameField = document.getElementById('purchaserNameField');
            purchaserNameField.style.display = (status === 'occupied') ? 'block' : 'none';
        }

        // Initialize field visibility on page load based on current selection
        document.addEventListener('DOMContentLoaded', togglePurchaserName);
    </script>
@endsection
