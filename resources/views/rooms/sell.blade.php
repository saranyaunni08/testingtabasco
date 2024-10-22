@extends('layouts.default')

@section('content')
<div class="header">
    <h2>TABASCO INN</h2>
    <div>Client Name: <strong>{{ $sale->client_name }}</strong></div>
    <div>Statement of Account</div>
    <div>From {{ $sale->start_date }} To {{ $sale->end_date }}</div>
    <div style="color: red;">Statement Type: Cash</div>
</div>

<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>V.No</th>
            <th>Description</th>
            <th>Payment Type</th>
            <th>Debit</th>
            <th>Credit</th>
            <th>Balance</th>
        </tr>
    </thead>
    <tbody>
        @php
            $balance = 0;
            $totalDebit = 0;
            $totalCredit = 0;
        @endphp

        @foreach($cashInstallments as $installment)
            @php
                $balance += $installment->debit - $installment->credit;
                $totalDebit += $installment->debit;
                $totalCredit += $installment->credit;
            @endphp
            <tr>
                <td>{{ $installment->date }}</td>
                <td>{{ $installment->voucher_no }}</td>
                <td>{{ $installment->description }}</td>
                <td>{{ $installment->payment_type }}</td>
                <td>{{ number_format($installment->debit, 2) }}</td>
                <td>{{ number_format($installment->credit, 2) }}</td>
                <td>{{ number_format($balance, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="subtotal">
            <td colspan="4">Sub Total</td>
            <td>{{ number_format($totalDebit, 2) }}</td>
            <td>{{ number_format($totalCredit, 2) }}</td>
            <td>{{ number_format($balance, 2) }}</td>
        </tr>
        <tr class="grand-total">
            <td colspan="4">Grand Total</td>
            <td>{{ number_format($totalDebit, 2) }}</td>
            <td>{{ number_format($totalCredit, 2) }}</td>
            <td>{{ number_format($balance, 2) }}</td>
        </tr>
    </tfoot>
</table>
@endsection
