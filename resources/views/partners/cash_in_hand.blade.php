@extends('layouts.default')

@section('content')

<div class="container">

    <h1>Partners' Cash In Hand Status</h1>

    <table class="table">
        <thead>
            <tr>
                <th>Partner Name</th>
                <th>Max In Hand Amount</th> <!-- New Column -->
                <th>Balance To Receive</th> <!-- New Column -->
                <th>Latest Sale Date</th> <!-- Existing Column -->
                <th>Action</th> <!-- New Column for Action -->
            </tr>
        </thead>
        <tbody>
            @foreach($partners as $partner)
                <tr>
                    <td>{{ $partner->first_name }} {{ $partner->last_name }}</td>
                    <td>${{ number_format($partner->max_in_hand_amount, 2) }}</td>
                    <td>${{ number_format($partner->max_in_hand_amount - $partner->max_cash_in_hand_paid_amount, 2) }}</td>
                    <td>{{ $partner->updated_at ? $partner->updated_at->format('Y-m-d') : 'N/A' }}</td>
                    <td>
                        <!-- Form to mark as paid -->
                        <form action="{{ route('admin.partners.mark_paid', $partner->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="payment_date_{{ $partner->id }}">Payment Date:</label>
                                <input type="date" id="payment_date_{{ $partner->id }}" name="payment_date" required>
                            </div>
                            <div class="form-group">
                                <label for="payment_amount_{{ $partner->id }}">Amount Paid:</label>
                                <input type="number" id="payment_amount_{{ $partner->id }}" name="payment_amount" step="0.01" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Mark as Paid</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>

@endsection
