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
                        @php
                            $roomTypes = [
                                'Flat' => [],
                                'Shops' => [],
                                'Car parking' => [],
                                'Table space' => [],
                                'Chair space' => [],
                                'Kiosk' => []
                            ];
                            foreach ($rooms as $room) {
                                $roomTypes[$room->room_type][] = $room;
                            }
                        @endphp

                        @foreach ($roomTypes as $type => $rooms)
                            @if (count($rooms) > 0)
                                <div class="table-responsive mb-4">
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                            <tr>
                                                <th colspan="10" class="bg-gradient-info text-white text-center font-weight-bold">{{ $type }}</th>
                                            </tr>
                                            <tr>
                                                @if ($type === 'Flat')
                                                    <th>Sl. No</th>
                                                    <th>Room Number</th>
                                                    <th>Flat Model</th>
                                                    <th>Total Sq. Ft</th>
                                                    <th>Total Sq. Rate</th>
                                                    <th>Expected Amount</th>
                                                    <th>Sale Amount</th>
                                                    <th>Total Amount</th>
                                                    <th>Actions</th>
                                                @elseif ($type === 'Shops')
                                                    <th>Sl. No</th>
                                                    <th>Room Number</th>
                                                    <th>Shop Type</th>
                                                    <th>Shop Area</th>
                                                    <th>Shop Rate</th>
                                                    <th>Expected Shop Amount</th>
                                                    <th>Actions</th>
                                                @elseif ($type === 'Car parking')
                                                    <th>Sl. No</th>
                                                    <th>Room Number</th>
                                                    <th>Parking Number</th>
                                                    <th>Parking Type</th>
                                                    <th>Parking Area</th>
                                                    <th>Parking Rate</th>
                                                    <th>Actions</th>
                                                @elseif ($type === 'Table space')
                                                    <th>Sl. No</th>
                                                    <th>Room Number</th>
                                                    <th>Space Name</th>
                                                    <th>Space Type</th>
                                                    <th>Space Area</th>
                                                    <th>Space Rate</th>
                                                    <th>Actions</th>
                                                @elseif ($type === 'Chair space')
                                                    <th>Sl. No</th>
                                                    <th>Room Number</th>
                                                    <th>Chair Name</th>
                                                    <th>Chair Type</th>
                                                    <th>Chair Material</th>
                                                    <th>Chair Price</th>
                                                    <th>Actions</th>
                                                @elseif ($type === 'Kiosk')
                                                    <th>Sl. No</th>
                                                    <th>Room Number</th>
                                                    <th>Kiosk Name</th>
                                                    <th>Kiosk Type</th>
                                                    <th>Kiosk Area</th>
                                                    <th>Kiosk Rate</th>
                                                    <th>Actions</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($rooms as $index => $room)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $room->room_number }}</td>
                                                    @if ($type === 'Flat')
                                                        <td>{{ $room->flat_model }}</td>
                                                        <td>{{ $room->total_sq_ft }}</td>
                                                        <td>{{ $room->total_sq_rate }}</td>
                                                        <td>{{ $room->expected_amount }}</td>
                                                        <td>{{ $room->sale_amount }}</td>
                                                        <td>{{ $room->total_amount }}</td>
                                                    @elseif ($type === 'Shops')
                                                        <td>{{ $room->shop_type }}</td>
                                                        <td>{{ $room->shop_area }}</td>
                                                        <td>{{ $room->shop_rate }}</td>
                                                        <td>{{ $room->shop_area * $room->shop_rate }}</td>
                                                    @elseif ($type === 'Car parking')
                                                        <td>{{ $room->parking_number }}</td>
                                                        <td>{{ $room->parking_type }}</td>
                                                        <td>{{ $room->parking_area }}</td>
                                                        <td>{{ $room->parking_rate }}</td>
                                                    @elseif ($type === 'Table space')
                                                        <td>{{ $room->space_name }}</td>
                                                        <td>{{ $room->space_type }}</td>
                                                        <td>{{ $room->space_area }}</td>
                                                        <td>{{ $room->space_rate }}</td>
                                                    @elseif ($type === 'Chair space')
                                                        <td>{{ $room->chair_name }}</td>
                                                        <td>{{ $room->chair_type }}</td>
                                                        <td>{{ $room->chair_material }}</td>
                                                        <td>{{ $room->chair_price }}</td>
                                                    @elseif ($type === 'Kiosk')
                                                        <td>{{ $room->kiosk_name }}</td>
                                                        <td>{{ $room->kiosk_type }}</td>
                                                        <td>{{ $room->kiosk_area }}</td>
                                                        <td>{{ $room->kiosk_rate }}</td>
                                                    @endif
                                                    <td>
                                                        @if ($room->status === 'available')
                                                        <!-- Button trigger modal -->
                                                        <button type="button" class="btn btn-success btn-sm me-2" data-toggle="modal" data-target="#sellModal{{ $room->id }}">
                                                            Sell
                                                        </button>
                                                    @endif
                                                    <a href="{{ route('admin.rooms.edit', $room->id) }}" class="btn btn-warning btn-sm me-2">Edit</a>
                                                    <form action="{{ route('admin.rooms.destroy', $room->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this room?');" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                    </form>
                                                    </td>
                                                </tr>

                                               <!-- Modal -->
<div class="modal fade" id="sellModal{{ $room->id }}" tabindex="-1" role="dialog" aria-labelledby="sellModalLabel{{ $room->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sellModalLabel{{ $room->id }}">Sell Room: {{ $room->room_number }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.sales.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="room_id" value="{{ $room->id }}">
                    <div class="form-group">
                        <label for="customer_name">Customer Name</label>
                        <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                    </div>
                    <div class="form-group">
                        <label for="customer_contact">Customer Contact</label>
                        <input type="text" class="form-control" id="customer_contact" name="customer_contact" required>
                    </div>
                    <div class="form-group">
                        <label for="customer_email">Customer Email</label>
                        <input type="email" class="form-control" id="customer_email" name="customer_email">
                    </div>
                    <div class="form-group">
                        <label for="sale_amount">Sale Amount</label>
                        <input type="number" step="0.01" class="form-control" id="sale_amount" name="sale_amount" required>
                    </div>
                    <div class="form-group">
                        <label for="parking_amount">Parking Amount</label>
                        <input type="number" step="0.01" class="form-control" id="parking_amount" name="parking_amount" required>
                    </div>
                    <div class="form-group">
                        <label for="gst_option">GST Option</label>
                        <select class="form-control" id="gst_option" name="gst_option">
                            <option value="add_gst">Add GST</option>
                            <option value="no_gst">Don't Add GST</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <hr>
                            @endif
                        @endforeach

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
