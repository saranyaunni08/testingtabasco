@extends('layouts.default', ['title' => 'Sale Details', 'page' => 'cancelled-sales'])

@section('content')
<div class="container-fluid py-4">
    <div class="invoice-box">
        <h2>Sale Details</h2>
        <div class="cancelled-status">Cancelled</div>
        <div class="details">
            <span>Sale ID: {{ $sale->id }}</span>
            <span>Room Type: {{ $sale->room->room_type }}</span><br>
            <span>Sale Amount: {{ $sale->sale_amount }}</span><br>
            <span>EMI Start Date: {{ \Carbon\Carbon::parse($sale->emi_start_date)->format('d/m/Y') }}</span>
            <span>EMI End Date: {{ \Carbon\Carbon::parse($sale->emi_end_date)->format('d/m/Y') }}</span><br>
            <span>EMI Amount: {{ $sale->emi_amount }}</span><br>
            <span>Tenure (Months): {{ $sale->tenure_months }}</span><br>
            <span>Remaining Balance: {{ $sale->remaining_balance_after_installments }}</span>
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
                @php
                    $remaining_tenure = $sale->tenure_months;
                @endphp
                @foreach($installments as $installment)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $installment->id }}</td>
                        <td>{{ \Carbon\Carbon::parse($installment->installment_date)->format('d/m/Y') }}</td>
                        <td>{{ $installment->installment_amount }}</td>
                        <td>{{ $installment->transaction_details }}</td>
                        <td>{{ $installment->bank_details }}</td>
                        <td>
                            @if($installment->status === 'paid')
                                <span class="badge bg-success">Paid</span>
                                @php
                                    $remaining_tenure--;
                                @endphp
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
