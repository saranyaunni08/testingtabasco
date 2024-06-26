@extends('layouts.default', ['title' => 'Kiosks'])

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <h5 class="card-header">Kiosks Table</h5>
        <div class="card-body">
            <div class="table-responsive">
                <table id="kiosksTable" class="table table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <td>Room Floor</td>
                            <th>Kiosk Name</th>
                            <th>Kiosk Type</th>
                            <th>Kiosk Area</th>
                            <th>Kiosk Rate</th>
                            <th>Kiosk Expected Rate</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rooms as $room)
                        @if ($room->room_type == 'Kiosk')
                        <tr>
                            <td>{{ $room->room_floor }}</td>
                            <td>{{ $room->kiosk_name }}</td>
                            <td>{{ $room->kiosk_type }}</td>
                            <td>{{ $room->kiosk_area }} sq ft</td>
                            <td>₹{{ number_format($room->kiosk_rate, 2) }}</td>
                            <td>₹{{ number_format($room->kiosk_expected_rate, 2) }}</td>
                            <td>
                                <a href="{{ route('admin.rooms.edit', $room->id) }}" class="btn btn-success btn-sm">
                                    <i class="bx bx-edit bx-sm"></i>
                                </a>
                                <form action="{{ route('admin.rooms.destroy', ['building_id' => $room->building_id, 'room_id' => $room->id]) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt bx-sm"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endif
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No kiosks available.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#kiosksTable').DataTable({
        scrollY: '300px',
        scrollX: true,
        scrollCollapse: true,
        paging: true,
    });
});
</script>
@endpush
