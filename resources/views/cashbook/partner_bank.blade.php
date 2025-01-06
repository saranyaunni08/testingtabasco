@extends('layouts.default')

@section('content')
<div class="container my-4">

<div style="text-align: right;">
    <a href="{{ route('admin.partner_bank_report.pdf', ['buildingId' => $building->id]) }}?partner_name={{ urlencode($partnerName) }}"
       class="btn btn-primary">
        <i class="fas fa-arrow-down"></i> Download PDF
    </a>
</div>


    <!-- Header -->
    <h3 class="text-center font-weight-bold">TABASCO INN</h3>
    <h4 class="text-center font-weight-bold">STATEMENT OF ACCOUNT</h4>
    <h5 class="text-center">{{ $partner->first_name }} Current Account</h5>
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
                $totalDebit = 0; // Initialize total debit
                $totalCredit = 0; // Initialize total credit
                $totalBalance = 0; // Initialize total balance
            @endphp

            @foreach ($installments as $installment)
                        @php
                            $paidAmount = floatval($installment->paid_amount); // Convert to float
                            $partnerAmount = floatval($installment->partner_amounts); // Convert to float
                            $balance = $paidAmount - $partnerAmount;

                            // Add to the running totals
                            $totalDebit += $paidAmount;
                            $totalCredit += $partnerAmount;
                            $totalBalance += $balance;
                        @endphp
                        <tr class="{{ $loop->iteration % 2 == 0 ? 'even-row' : 'odd-row' }}">
                            <td>{{ \Carbon\Carbon::parse($installment->payment_date)->format('d-m-Y') }}</td>
                            <td></td>
                            <td>{{ $installment->installment_number }} installment ({{ $installment->first_name }})</td>
                            <td>{{ number_format($paidAmount, 2) }}</td>
                            <td>{{ number_format($partnerAmount, 2) }}</td>
                            <td>{{ number_format($balance, 2) }}</td> <!-- Balance after each installment -->
                            <td></td> <!-- Empty cell for any additional info -->
                        </tr>
            @endforeach

        </tbody>
        <tfoot>
            <tr>
                <td colspan="3"><strong>Sub Total</strong></td>
                <td><strong>{{ number_format($totalDebit, 2) }}</strong></td>
                <td><strong>{{ number_format($totalCredit, 2) }}</strong></td>
                <td><strong>{{ number_format($totalBalance, 2) }}</strong></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3"><strong>Sub Total</strong></td>
                <td><strong>{{ number_format($totalDebit, 2) }}</strong></td>
                <td><strong>{{ number_format($totalCredit, 2) }}</strong></td>
                <td><strong>{{ number_format($totalBalance, 2) }}</strong></td>
                <td></td>
            </tr>

        </tfoot>

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