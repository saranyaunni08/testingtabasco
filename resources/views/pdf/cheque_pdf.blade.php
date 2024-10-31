@extends('layouts.default')

@section('content')
<div class="container">
    <div class="text-center">
        <h2>TABASCO INN</h2>
        <h4><strong>Client Name:</strong> {{ $sale->customer_name }}</h4>
        <h4><u>Statement of Account</u></h4>
        <p>From <strong>{{ \Carbon\Carbon::parse($firstInstallmentDate)->format('d/m/Y') }}</strong> 
           To <strong>{{ \Carbon\Carbon::parse($lastInstallmentDate)->format('d/m/Y') }}</strong></p>
        <p class="text-danger">Statement Type: Cheque</p>
    </div>

    <table class="table table-bordered mt-4">
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
                $initialBalance = $installments->sum('installment_amount'); // Change here
                $currentBalance = $initialBalance;
                $totalDebit = 0;
                $totalCredit = 0;
                $firstRow = true;
            @endphp
    
            @foreach($installments as $installment) // Change here
                @php
                    $credit = $installment->status === 'paid' ? $installment->installment_amount : $installment->credit;
                    $totalDebit += $installment->debit;
                    // Other calculations...
                @endphp
                <tr>
                    <td>{{ $installment->installment_date }}</td>
                    <td>{{ $installment->v_no }}</td>
                    <td>{{ $installment->description }}</td>
                    <td>{{ $installment->payment_type }}</td>
                    <td>{{ $installment->debit }}</td>
                    <td>{{ $credit }}</td>
                    <td>{{ $currentBalance }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
        
</div>
@endsection
