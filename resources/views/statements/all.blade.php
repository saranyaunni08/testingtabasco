@extends('layouts.default')

@section('content')
<div class="container mt-5" style="background-color: #f9f9f9; border-radius: 10px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); padding: 20px;">
    <div class="text-center mb-4">
        <h2 class="font-weight-bold" style="color: #333;">TABASCO INN</h2>
        <p class="mb-1"><strong>Client Name:</strong> {{ $sale->client_name }}</p>
        <p class="mb-1" style="color: #555;"><strong>Statement of Account</strong></p>
        <p class="mb-1" style="color: #555;">
            <strong>From:</strong> 01-09-2024 
            <strong>To:</strong> 18-05-2026
        </p>
        <p style="color: #555;"><strong>Statement Type:</strong> All</p>
    </div>

    <table class="table table-bordered table-striped" style="background-color: white;">
        <thead class="thead-dark" style="background-color: #343a40; color: white;">
            <tr>
                <th class="text-center">Date</th>
                <th class="text-center">V.No</th>
                <th class="text-center">Description</th>
                <th class="text-center">Payment Type</th>
                <th class="text-center">Debit</th>
                <th class="text-center">Credit</th>
                <th class="text-center">Balance</th>
            </tr>
        </thead>
        <tbody>
            @php $balance = 0; @endphp
            @foreach ($transactions as $transaction)
                @php
                    $balance += $transaction['debit'] - $transaction['credit'];
                @endphp
                <tr>
                    <td class="text-center">{{ \Carbon\Carbon::parse($transaction['date'])->format('d/m/Y') }}</td>
                    <td class="text-center"></td> <!-- V.No placeholder -->
                    <td>{{ $transaction['description'] }}</td>
                    <td class="text-center">{{ $transaction['payment_type'] }}</td>
                    <td class="text-right">{{ number_format($transaction['debit'], 2) }}</td>
                    <td class="text-right">{{ number_format($transaction['credit'], 2) }}</td>
                    <td class="text-right">{{ number_format($balance, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-right">Sub Total</th>
                <th class="text-right">{{ number_format($transactions->sum('debit'), 2) }}</th>
                <th class="text-right">{{ number_format($transactions->sum('credit'), 2) }}</th>
                <th class="text-right">{{ number_format($balance, 2) }}</th>
            </tr>
            <tr>
                <th colspan="4" class="text-right">Grand Total</th>
                <th class="text-right">{{ number_format($transactions->sum('debit'), 2) }}</th>
                <th class="text-right">{{ number_format($transactions->sum('credit'), 2) }}</th>
                <th class="text-right">{{ number_format($balance, 2) }}</th>
            </tr>
        </tfoot>
    </table>
</div>
@endsection
