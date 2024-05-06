@extends('layouts.default', ['title' => 'Dashboard', 'page' => 'building'])

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header" style="background-color: blue;">
                    <h5 class="text-center" style="color: white;">BUILDING LIST</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <a href="{{ route('admin.addbuilding') }}" class="btn btn-primary btn-md" style="background-color: green;">Add Building</a>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Number of Floors</th>
                                <th>Amenities</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($buildings as $building)
                            <tr>
                                <td>{{ $building->building_name }}</td>
                                <td>{{ $building->building_address }}</td>
                                <td>{{ $building->no_of_floors }}</td>
                                <td>{{ $building->building_amenities }}</td>
                               
                                <td>
                                    <form method="POST" action="{{ route('admin.building.delete', ['id' => $building->id]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
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

@endsection
