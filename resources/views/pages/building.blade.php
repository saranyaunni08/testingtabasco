@extends('layouts.default', ['title' => 'Building View', 'page' => 'buildings'])
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-info border-radius-lg p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize ps-3 mb-0">Buildings</h6>
                            <div class="pe-3">
                                <a href="{{ route('admin.addbuilding') }}" class="btn btn-light m-0">Add Building</a>
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
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($buildings as $building)
                                    <tr>
                                        <td>
                                            <h6 class="mb-0 text-xs">{{ $building->building_name }}</h6>
                                        </td>
                                        <td>
                                            <p class="text-xs text-dark font-weight-normal mb-0">{{ $building->building_address }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs text-dark font-weight-normal mb-0">{{ $building->no_of_floors }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs text-dark font-weight-normal mb-0">{{ $building->super_built_up_area }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs text-dark font-weight-normal mb-0">{{ $building->carpet_area }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs text-dark font-weight-normal mb-0">
                                                @foreach(explode(',', $building->building_amenities) as $amenity)
                                                  {{ $amenity }},
                                                @endforeach
                                                 @if ($building->additional_amenities)
                                                  {{ $building->additional_amenities }}
                                                 @endif
                                            </p>
                                        </td>
                                        <td>
                                            <p class="text-xs text-dark font-weight-normal mb-0">{{ $building->city }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs text-dark font-weight-normal mb-0">{{ $building->state }}</p>
                                        </td>
                                      

                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.buildings.show', ['id' => $building->id]) }}" class="btn btn-sm btn-success me-1">View</a>
                                                <a href="{{ route('admin.building.editbuilding', ['id' => $building->id]) }}" class="btn btn-sm btn-primary me-1">Edit</a>
                                                <form method="POST" action="{{ route('admin.building.delete', ['id' => $building->id]) }}">
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
