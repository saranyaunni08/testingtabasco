@extends('layouts.default', ['title' => 'Chair Space'])

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <h5 class="card-header">Chair Space</h5>
        <div class="card-body">
            <div class="table-responsive">
                <table id="chairSpacesTable" class="table table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Chair Name</th>
                            <th>Chair Type</th>
                            <th>Chair Space (sq.)</th>
                            <th>Chair Space Rate (sq.)</th>
                            <th>Chair Space Expected Rate</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($chairSpaces as $room)
                        <tr>
                            <td>{{ $room->chair_name }}</td>
                            <td>{{ $room->chair_type }}</td>
                            <td>{{ $room->chair_space }}</td>
                            <td>{{ $room->chair_space_rate }}</td>
                            <td>{{ $room->chair_space_expected_rate }}</td>
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
                            <td colspan="6" class="text-center">No chair spaces available.</td>
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
    $('#chairSpacesTable').DataTable({
        scrollY: '300px',
        scrollX: true,
        scrollCollapse: true,
        paging: true,
    });
});
</script>
@endpush
