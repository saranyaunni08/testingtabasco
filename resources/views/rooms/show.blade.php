@extends('layouts.default', ['title' => 'Rooms'])

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-info border-radius-lg p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="pe-3">
                                <a href="{{ route('admin.rooms.create') }}" class="btn btn-light m-0">Add Room</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="row p-4">
                        <div class="table-responsive">
                            <table class="table align-items-centerw mb-0">
                                <br>
                                <tbody>
                                    @foreach ($rooms as $room)
                                    <tr>
                                        <td>
                                            <p class="text-xs text-center text-dark font-weight-bold mb-0">{{ $room->room_type }}</p>
                                            @foreach ($room->getAttributes() as $key => $value)
                                                @if (!is_null($value) && !in_array($key, ['id', 'building_id', 'created_at', 'updated_at', 'deleted_at']))
                                                    <div class="border p-2 mb-2 @if($key == 'status' && $value == 'sold') bg-danger text-white @endif">
                                                        <div class="font-weight-bold">{{ ucfirst(str_replace('_', ' ', $key)) }}</div>
                                                        <div>{{ $value }}</div>
                                                    </div>
                                                @endif
                                            @endforeach
                                            <div class="d-flex justify-content-center mt-2">
                                                @if ($room->status != 'sold')
                                                    <a href="{{ route('admin.sales.create', ['room_id' => $room->id]) }}" class="btn btn-success btn-sm me-2">Sell</a>
                                                @endif
                                                <a href="{{ route('admin.rooms.edit', $room->id) }}" class="btn btn-warning btn-sm me-2">Edit</a>
                                                <form action="{{ route('admin.rooms.destroy', $room->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this room?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
