@extends('layouts.default', ['title' => 'Sale Details', 'page' => 'cancelled-sales'])

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm border-light">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Sale Details</h2>
        </div>
        <div class="card-body">
            <div class="alert alert-warning mb-4">
                <strong>Status:</strong> Cancelled
            </div>
            <div class="row">
                <div class="col-md-6">
                    <ul class="list-unstyled">
                        <li><strong>Sale ID:</strong> {{ $sale->id }}</li>
                        <li><strong>Room Number:</strong> {{ $sale->room->room_number }}</li>
                        <li><strong>Room Type:</strong> {{ $sale->room->room_type }}</li>
                        <li><strong>Sale Amount:</strong> {{ number_format($sale->sale_amount, 2) }}</li>
                        <li><strong>First Installment Date:</strong> {{ $firstInstallmentDate ? \Carbon\Carbon::parse($firstInstallmentDate)->format('d/m/Y') : 'N/A' }}</li>
                        <li><strong>Last Installment Date:</strong> {{ $lastInstallmentDate ? \Carbon\Carbon::parse($lastInstallmentDate)->format('d/m/Y') : 'N/A' }}</li>
                        <li><strong>EMI Amount:</strong> {{ $firstInstallment ? number_format($firstInstallment->installment_amount, 2) : 'N/A' }}</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul class="list-unstyled">
                        <li><strong>Total Tenure (Months):</strong> {{ $totalTenureMonths }}</li>
                        <li><strong>Remaining Tenure (Months):</strong> {{ $remainingTenureMonths }}</li>
                        <li><strong>Remaining Balance:</strong> {{ number_format($remainingBalance, 2) }}</li>
                        <li><strong>Received Amount:</strong> {{ number_format($receivedAmount, 2) }}</li>
                        <li><strong>Cancellation Fine Amount:</strong> {{ number_format($cancellationFineAmount, 2) }}</li>
                        <li><strong>Advance Amount:</strong> {{ number_format($sale->advance_amount, 2) }}</li>
                        <li><strong>Cash in Hand Amount:</strong> {{ number_format($sale->in_hand_amount, 2) }}</li>
                        <li><strong>Cash in Hand Amount Received:</strong> {{ number_format($sale->cash_in_hand_paid_amount, 2) }}</li>
                        <li><strong>Amount to Pay Back:</strong> {{ number_format($amountToPayBack, 2) }}</li>
                    </ul>
                </div>
            </div>

            <h4 class="mt-4 mb-3">Installment Details</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
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
    </div>
</div>

<!-- Custom CSS for additional styling -->
<style>
    .card-header {
        border-bottom: 1px solid #ddd;
    }
    .alert-warning {
        font-weight: bold;
    }
    .table thead th {
        background-color: #343a40;
        color: white;
    }
    .badge {
        font-size: 0.875rem;
        font-weight: 700;
    }
</style>
@endsection
 