<!-- resources/views/rooms/flats.blade.php -->

@extends('layouts.default', ['title' => 'Flats'])

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        @foreach ($rooms as $room)
        <div class="col-xl-3 col-lg-4 col-sm-5 col-7 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <img src="{{ asset('img/image.png') }}" alt="Flat Image"  class="rounded">
                        </div>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" id="cardOpt1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="cardOpt1">
                                <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                            </div>
                        </div>
                    </div>
                    <span class="fw-medium d-block mb-1">Flat</span>
                    <h4 class="card-title mb-2">{{ $room->flat_model }}</h4>
                    <p>Room Number: {{ $room->room_number }}</p>
                    <p>Carpet Area: {{ $room->flat_carpet_area }} sq ft</p>
                    <p>Price: ₹{{ number_format($room->flat_expected_carpet_area_price, 2) }}</p>
                </div> 
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
