@extends('layouts.default')

@section('content')
<div class="container my-4">
    <!-- Header -->
    <h3 class="text-center font-weight-bold">TABASCO INN</h3>
    <h4 class="text-center font-weight-bold">STATEMENT OF ACCOUNT</h4>
    <h5 class="text-center">BASHEER CURRENT ACCOUNT</h5>
    <p style="text-align: center;">
        From
        {{ $installments->min('installment_date') ? \Carbon\Carbon::parse($installments->min('installment_date'))->format('d-m-Y') : 'N/A' }}
        To
        {{ $installments->max('installment_date') ? \Carbon\Carbon::parse($installments->max('installment_date'))->format('d-m-Y') : 'N/A' }}
    </p>


    <!-- Table -->
    <table class="table table-bordered text-center">
    <thead>
        <tr class="table-header">
            <th>Date</th>
            <th>Vno</th>
            <th>Description</th>
            <th>Debit</th>
            <th>Credit</th>
            <th>Balance</th>
        </tr>
    </thead>
    <tbody>
        @php
            $balance = 0; // Initialize balance
        @endphp

        @foreach ($installments as $installment)
            @php
                $balance += $installment->paid_amount; // Calculate balance dynamically
            @endphp
            <tr class="{{ $loop->iteration % 2 == 0 ? 'even-row' : 'odd-row' }}">
                <td>{{ \Carbon\Carbon::parse($installment->installment_date)->format('d-m-Y') }}</td>
                <td>{{ $installment->installment_number }} ({{ $installment->first_name }})</td>
                <td>{{ number_format($installment->paid_amount, 2) }}</td>
                <td></td> <!-- Empty cell for debit -->
                <td>{{ number_format($balance, 2) }}</td> <!-- Balance after each installment -->
                <td></td> <!-- Empty cell for any additional info -->
            </tr>
        @endforeach
    </tbody>
</table>

</div>

<!-- Inline Style -->
<style>
    body {
        font-family: Arial, sans-serif;
    }

    .table-header {
        background-color: #f58220;
        /* Orange header */
        color: #fff;
        text-align: center;
    }

    .sub-header {
        background-color: #f2f2f2;
        font-weight: bold;
        text-align: center;
    }

    .even-row {
        background-color: #e0f4ff;
        /* Light Blue */
    }

    .odd-row {
        background-color: #d7f7e8;
        /* Light Green */
    }

    .table td,
    .table th {
        vertical-align: middle;
    }
</style>
@endsection