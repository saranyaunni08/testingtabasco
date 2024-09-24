@extends('layouts.default', ['title' => 'Custom Rooms'])

@section('content')
<div class="container-fluid py-4">

    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-info border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                        <h6 class="text-white text-capitalize ps-3">Room Details</h6>
                        <a href="{{ route('admin.rooms.create', ['building_id' => $building_id, 'room_type' => 'Custom']) }}" 
                            class="btn btn-outline-light float-end" style="background-color: #ffffff; border-color: #007bff;
                             color: #007bff; font-weight: bold;" onmouseover="this.style.backgroundColor='#007bff'; this.style.color='#ffffff'" 
                             onmouseout="this.style.backgroundColor='#ffffff'; this.style.color='#007bff'">Add Room</a>
                    </div>
                </div>
            </div>

            <div class="card">
                <h5 class="card-header"> Rooms Table</h5>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="customRoomsTable" class="table table-bordered" style="width:100%">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Room Floor</th>
                                    <th> Name</th>
                                    <th> Type</th>
                                    <th> Area</th>
                                    <th> Rate</th>
                                    <th>Expected  Rate</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rooms as $room)
                                <tr>
                                    <td>{{ $room->room_floor }}</td>
                                    <td>{{ $room->custom_name }}</td>
                                    <td>{{ $room->custom_type }}</td>
                                    <td>{{ $room->custom_area }} sq ft</td>
                                    <td>₹{{ number_format($room->custom_rate, 2) }}</td>
                                    <td>₹{{ number_format($room->expected_custom_rate, 2) }}</td>
                                    <td>
                                        @php
                                        $sale = $room->sales->first();
                                        $isPaid = $sale && $installments->where('sale_id', $sale->id)->where('status', 'paid')->isNotEmpty();
                                    @endphp
                                    

                                        @if($room->status == 'available')
                                            <span class="badge badge-info">Available</span>
                                        @elseif($isPaid)
                                            <span class="badge badge-success">Sold</span>
                                        @else
                                            <span class="badge badge-warning">Booking</span>
                                        @endif
                                    </td>
                                    <td class="d-flex">
                                        @if($room->status == 'available' || $room->status == 'booking')
                                        <button type="button" class="btn btn-success btn-sm me-2" data-toggle="modal" data-target="#authModal{{ $room->id }}" data-building-id="{{ $room->building_id }}" data-room-id="{{ $room->id }}" data-action="edit">
                                            <i class="bx bx-edit bx-sm"></i>
                                        </button>

                                        <button type="button" class="btn btn-danger btn-sm me-2" data-toggle="modal" data-target="#deleteModal{{ $room->id }}" data-building-id="{{ $room->building_id }}" data-room-id="{{ $room->id }}" data-action="delete">
                                            <i class="fas fa-trash-alt bx-sm"></i>
                                        </button>
                                        @endif

                                        @if ($room->status === 'available')
                                        <a href="{{ route('admin.rooms.sell', ['room' => $room->id, 'buildingId' => $room->building_id]) }}" class="btn btn-primary">Sell Room</a>

                                        @elseif ($room->status === 'sold')
                                            @if ($room->sale)
                                                <a href="{{ route('admin.customers.show', ['saleId' => $room->sale->id]) }}"
                                                   style="color: #28a745; font-weight: bold; font-size: 1.2em; border: 2px solid #28a745; padding: 5px 10px; border-radius: 5px; background-color: #e9f7ef; text-decoration:none;">
                                                   View
                                                </a>
                                            @else
                                                <button type="button" class="btn btn-secondary btn-sm me-2" disabled>
                                                    No Sale Info
                                                </button>
                                            @endif

                                        @else
                                            <button type="button" class="btn btn-secondary btn-sm me-2" disabled>
                                                Not Available
                                            </button>
                                        @endif

                                        <!-- Authentication Modals Here -->

                                    </td>
                                </tr>
                                @empty
                                <tr>
                                <td colspan="8" class="text-center">No records found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
