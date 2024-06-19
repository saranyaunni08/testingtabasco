@extends('layouts.default', ['title' => 'Rooms'])

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-info border-radius-lg p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="pe-3"></div>
                            <div>
                                <h6 class="text-white text-capitalize ps-3">Rooms Dashboard</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="row p-4">
                        <div class="container-fluid mt-3">
                            <div class="row">
                                <div class="col-lg-12 ">
                                    <a href="{{ route('admin.rooms.show', ['buildingId' => $building->id]) }}" class="btn btn-primary">View Rooms</a>
                                </div>

                                @foreach($rooms as $room)
                                <div class="col-lg-3 col-sm-6">
                                  <div class="card gradient-1">
                                    <div class="card-body">
                                      <h3 class="card-title text-black">{{ $room->room_type }}</h3>
                                      <div class="d-inline-block">
                                        @php
                                          $status = $room->status; // Assuming another status variable
                                          $progressColor = $status == 'occupied' ? 'bg-danger' : 'bg-info'; // Set color based on status
                                        @endphp
                                        <p class="text-muted mb-1">{{ ucfirst($status) }}</p>
                                        <div class="progress">
                                          <div class="progress-bar {{ $progressColor }}" role="progressbar"
                                            aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"
                                            style="width: 500%"> </div>
                                        </div>
                                      </div>
                                      <p class="text-muted mb-1">Another Status: Progress</p>
                                      <div class="progress">
                                        <div class="progress-bar bg-warning" role="progressbar"
                                          aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"
                                          style="width: 50%"> </div>
                                      </div>
                                    </div>
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

<!-- Include Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
@endsection
