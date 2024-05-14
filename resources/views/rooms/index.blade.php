@extends('layouts.default', ['title' => 'Room View', 'page' => 'rooms'])
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-info border-radius-lg p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3 mb-0">Rooms</h6>
                            <div class="pe-3">
                                <a href="{{ route('admin.rooms.create') }}" class="btn btn-light m-0">Add Rooms</a>
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
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Room Number</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Floor</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Type</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Built up Area</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Carpet Area</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Flate area</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Super build up price</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Carpet Area price</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($flats as $flat)
                                    <tr>
                                        <td>
                                            <h6 class="mb-0 text-xs">{{ $flat->room_number }}</h6>
                                        </td>
                                        <td>
                                            <p class="text-xs text-dark font-weight-normal mb-0">{{ $flat->room_floor }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs text-dark font-weight-normal mb-0">{{ $flat->room_type }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs text-dark font-weight-normal mb-0">{{ $flat->build_up_area }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs text-dark font-weight-normal mb-0">{{ $flat->carpet_area }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs text-dark font-weight-normal mb-0">{{ $flat->flat_rate }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs text-dark font-weight-normal mb-0">{{ $flat->super_build_up_price }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs text-dark font-weight-normal mb-0">{{ $flat->carpet_area_price }}</p>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                            <a href="{{ route('admin.rooms.edit', $flat->id) }}" class="btn btn-sm btn-primary me-1">Edit</a>
                                                <form method="POST" action="{{ route('admin.rooms.destroy', $flat->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this room?')">Delete</button>
                                                 
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
