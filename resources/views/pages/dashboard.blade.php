@extends('layouts.default', ['title' => 'Dashboard', 'page' => 'dashboard'])

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-info border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Total Buildings</h6>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="row p-4">
                        <div class="container-fluid mt-3">
                            <div class="row">
                                @foreach($buildings as $building)
                                <div class="col-lg-3 col-sm-6">
                                    <div class="card gradient-1">
                                        <a href="{{ route('admin.buildings.show', ['id' => $building->id]) }}">
                                            <div class="card-body">
                                                <h3 class="card-title text-black">{{ $building->building_name }}</h3>
                                                <div class="d-inline-block">
                                                    @php
                                                    $soldRoomsCount = $building->rooms()->where('status', 'sold')->count();
                                                    $availableRoomsCount = $building->rooms()->where('status', 'available')->count();
                                                    $totalRoomsCount = $soldRoomsCount + $availableRoomsCount;
                                                    $soldPercentage = $totalRoomsCount > 0 ? ($soldRoomsCount / $totalRoomsCount) * 100 : 0;
                                                    $availablePercentage = 100 - $soldPercentage;
                                                    @endphp
                                                    <p class="text-gray mb-0">Sold {{ $soldPercentage }}%
                                                        <div class="progress mb-2">
                                                            <div class="progress-bar bg-success" role="progressbar"
                                                                style="width: {{ $soldPercentage }}%" aria-valuenow="{{ $soldPercentage }}"
                                                                aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </p>
                                                    <p class="text-gray mb-0">Available {{ $availablePercentage }}%
                                                        <div class="progress">
                                                            <div class="progress-bar bg-warning" role="progressbar"
                                                                style="width: {{ $availablePercentage }}%"
                                                                aria-valuenow="{{ $availablePercentage }}" aria-valuemin="0"
                                                                aria-valuemax="100"></div>
                                                        </div>
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
