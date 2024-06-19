@extends('layouts.default', ['title' => $building->building_name ?? 'Buildings', 'page' => 'rooms'])

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-info border-radius-lg p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3 mb-0">{{ $building->building_name }}</h6>
                            <div class="pe-3">
                                <a href="{{ route('admin.rooms.create', ['building_id' => $building->id]) }}" class="btn btn-light m-0">Add Room</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="row p-4">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Location</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Number of Floors</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Super Built up Area</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Carpet Area</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Amenities</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">City</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">State</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($building->rooms as $room)
                                    <tr>
                                        <td><h6 class="mb-0 text-xs">{{ $room->name }}</h6></td>
                                        <td><p class="text-xs text-dark font-weight-normal mb-0">{{ $room->location }}</p></td>
                                        <td><p class="text-xs text-dark font-weight-normal mb-0">{{ $room->number_of_floors }}</p></td>
                                        <td><p class="text-xs text-dark font-weight-normal mb-0">{{ $room->super_built_up_area }}</p></td>
                                        <td><p class="text-xs text-dark font-weight-normal mb-0">{{ $room->carpet_area }}</p></td>
                                        <td><p class="text-xs text-dark font-weight-normal mb-0">{{ $room->amenities }}</p></td>
                                        <td><p class="text-xs text-dark font-weight-normal mb-0">{{ $room->city }}</p></td>
                                        <td><p class="text-xs text-dark font-weight-normal mb-0">{{ $room->state }}</p></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.rooms.edit', ['room' => $room->id]) }}" class="btn btn-sm btn-primary me-1">Edit</a>
                                                <form method="POST" action="{{ route('admin.rooms.destroy', ['room' => $room->id]) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger me-1">Delete</button>
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
