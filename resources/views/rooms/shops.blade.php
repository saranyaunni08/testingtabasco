@extends('layouts.default', ['title' => 'Shops'])

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <h5 class="card-header">Shops Table</h5>
        <div class="card-body">
            <div class="table-responsive">
                <table id="shopsTable" class="table table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Room Floor</th>
                            <th>Room Type</th>
                            <th> Build Up Area</th>
                            <th> Build Up Area Price</th>
                            <th> Expected Super Build Area Price</th>
                            <th> Carpet Area</th>
                            <th> Carpet Area Price</th>
                            <th> Expected Carpet Area Price</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rooms as $room)
                        <tr>
                            <td>{{ $room->room_floor }}</td>
                            <td>{{ $room->room_type }}</td>
                            <td>{{ $room->build_up_area }} sq ft</td>
                            <td>{{ $room->super_build_up_price }} sq ft</td>
                            <td>₹{{ number_format($room->expected_super_buildup_area_price, 2) }}</td>
                            <td>{{ $room->carpet_area }} sq ft</td>
                            <td>{{ $room->carpet_area_price }} sq ft</td>
                            <td>₹{{ number_format($room->expected_carpet_area_price, 2) }}</td>
                            <td>
                                @if($room->status == 'available')
                                    <span class="badge badge-info">Available</span>
                                @elseif($room->status == 'sold')
                                    <span class="badge badge-danger">Sold</span>
                                @endif
                            </td>
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
                        @empty
                        <tr>
                            <td colspan="11" class="text-center">No shops available.</td>
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
    $('#shopsTable').DataTable({
        scrollY: '300px',
        scrollX: true,
        scrollCollapse: true,
        paging: true,
    });
});
</script>
@endpush
