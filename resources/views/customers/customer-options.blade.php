@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Customer Options</h1>

    <p><strong>Sale ID:</strong> {{ $sale->id }}</p>
    <p><strong>Room:</strong> {{ $sale->room->name }}</p>
    <p><strong>Status:</strong> {{ $sale->status }}</p>

    <hr>

    <!-- Cancel Customer Form -->
    <form action="{{ route('customer.cancel', $sale->id) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-danger">Cancel Customer</button>
    </form>

    <hr>

    <!-- Exchange Room Form -->
    <form action="{{ route('customer.exchange', $sale->id) }}" method="POST">
        @csrf
        <label for="new_room_id">Select New Room:</label>
        <select name="new_room_id" id="new_room_id" class="form-control">
            @foreach ($rooms as $room)
                <option value="{{ $room->id }}">{{ $room->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary mt-2">Exchange Room</button>
    </form>

    <hr>

    <!-- Add Multiple Rooms to Installment Form -->
    <form action="{{ route('installment.addRooms', $sale->installments->first()->id ?? 0) }}" method="POST">
        @csrf
        <label>Select Rooms for Installment:</label>
        <select name="room_ids[]" class="form-control" multiple>
            @foreach ($rooms as $room)
                <option value="{{ $room->id }}">{{ $room->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-success mt-2">Add Rooms to Installment</button>
    </form>

    <hr>

    <h2>Installment Details</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Amount</th>
                <th>Rooms</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sale->installments as $installment)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($installment->installment_date)->format('d-m-Y') }}</td>
                    <td>₹{{ number_format($installment->installment_amount, 2) }}</td>
                    <td>
                        @php
                            $roomIds = json_decode($installment->room_ids);
                        @endphp
                        @foreach ($roomIds as $roomId)
                            {{ $rooms->find($roomId)->name ?? 'N/A' }},
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
