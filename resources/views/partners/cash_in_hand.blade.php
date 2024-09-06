@extends('layouts.default')

@section('content')

<div class="container mt-5">
    <h1 class="mb-4">{{ $title }}</h1>

    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Sale ID</th>
                <th>Partner Name</th>
                <th>Phone Number</th>
                <th>Address</th>
                <th>Customer Name</th>
                <th>Room Rate</th>
                <th>Cash In Hand Percent</th>
                <th>In Hand Amount</th>
                <th>Loan Type</th>
                <th>Percentage</th>
                <th>Amount Received</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $partnerData)
                @foreach($partnerData['partners'] as $partner)
                    <tr>
                        <td>{{ $partner['sale_id'] }}</td>
                        <td>{{ $partner['partner_name'] }}</td>
                        <td>{{ $partner['phone_number'] }}</td>
                        <td>{{ $partner['address'] }}</td>
                        <td>{{ $partner['customer_name'] }}</td>
                        <td>₹{{ number_format($partner['room_rate'], 2) }}</td>
                        <td>{{ $partner['cash_in_hand_percent'] }}%</td>
                        <td>₹{{ number_format($partner['in_hand_amount'], 2) }}</td>
                        <td>{{ $partner['loan_type'] }}</td>
                        <td>{{ $partner['percentage'] }}%</td>
                        <td>₹{{ number_format($partner['amount_received'], 2) }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>

@endsection
