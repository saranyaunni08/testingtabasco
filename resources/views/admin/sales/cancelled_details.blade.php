@extends('layouts.default', ['title' => 'Sale Details', 'page' => 'cancelled-sales'])

@section('content')
<div class="container-fluid py-4">
    <div class="invoice-box">
        <h2>Sale Details</h2>
        <div class="cancelled-status">Cancelled</div>
        <div class="details">
            <span>Sale ID: {{ $sale->id }}</span><br>
            <span>Room Number: {{ $sale->room->room_number }}</span><br>
            <span>Room Type: {{ $sale->room->room_type }}</span><br>
            <span>Sale Amount: {{ number_format($sale->sale_amount, 2) }}</span><br>
            <span>First Installment Date: {{ $firstInstallmentDate ? \Carbon\Carbon::parse($firstInstallmentDate)->format('d/m/Y') : 'N/A' }}</span><br>
            <span>Last Installment Date: {{ $lastInstallmentDate ? \Carbon\Carbon::parse($lastInstallmentDate)->format('d/m/Y') : 'N/A' }}</span><br>
            <span>EMI Amount: {{ $firstInstallment ? number_format($firstInstallment->installment_amount, 2) : 'N/A' }}</span><br>
            <span>Total Tenure (Months): {{ $totalTenureMonths }}</span><br>
            <span>Remaining Tenure (Months): {{ $remainingTenureMonths }}</span><br>
            <span>Remaining Balance: {{ number_format($remainingBalance, 2) }}</span><br>
            <span>Received Amount: {{ number_format($receivedAmount, 2) }}</span><br>
            <span>Cancellation Fine Amount: {{ number_format($cancellationFineAmount, 2) }}</span><br>
            <span>Advance Amount: {{ number_format($sale->advance_amount, 2) }}</span><br>
            <span>Cash in Hand Amount: {{ number_format($sale->in_hand_amount, 2) }}</span><br>
            <span>Cash in Hand Amount Received: {{ number_format($sale->cash_in_hand_paid_amount, 2) }}</span><br>
            <span>Amount to Pay Back: {{ number_format($amountToPayBack, 2) }}</span> <!-- Show the amount to be paid back -->
        </div>

        <h4 class="mt-4">Installment Details</h4>
        <table class="installments-table">
            <thead>
                <tr>
                    <th>SL No</th>
                    <th>ID</th>
                    <th>Installment Date</th>
                    <th>Amount</th>
                    <th>Transaction Details</th>
                    <th>Bank Details</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($installments as $installment)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $installment->id }}</td>
                        <td>{{ \Carbon\Carbon::parse($installment->installment_date)->format('d/m/Y') }}</td>
                        <td>{{ number_format($installment->installment_amount, 2) }}</td>
                        <td>{{ $installment->transaction_details }}</td>
                        <td>{{ $installment->bank_details }}</td>
                        <td>
                            @if($installment->status === 'paid')
                                <span class="badge bg-success">Paid</span>
                            @else
                                <span class="badge bg-danger">Pending</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
