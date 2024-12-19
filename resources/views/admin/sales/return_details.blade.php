@extends('layouts.default')

@section('content')
<div class="container my-5">
    <h2 class="text-center mb-5">Sale Details for {{ $sale->customer_name }}</h2>

    <!-- Sale Summary Section -->
    <div class="row mb-4">
        <!-- Existing Cards -->
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Total Cash Received by Installments</div>
                <div class="card-body">
                    <h5 class="card-title">₹{{ number_format($totalCashReceivedByInstallments, 2) }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-info mb-3">
                <div class="card-header">Received Cash Value</div>
                <div class="card-body">
                    <h5 class="card-title">₹{{ number_format($receivedCashValue, 2) }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Total Received Cash Value</div>
                <div class="card-body">
                    <h5 class="card-title">₹{{ number_format($totalReceivedAfterDeductions, 2) }}</h5>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Total Returned Amount</div>
                <div class="card-body">
                    <h5 class="card-title">₹{{ number_format($totalReturnedAmount, 2) }}</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Deductions Section -->
    <div class="card mb-5">
        <div class="card-header bg-dark text-white">
            Cash Deductions
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Deducted Amount</th>
                        <th>Deduction Reason</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->cashDeductions as $deduction) <!-- Reference $sale->cashDeductions -->
                        <tr>
                            <td>₹{{ number_format($deduction->deducted_amount, 2) }}</td>
                            <td>{{ $deduction->deduction_reason }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Returns Form Section -->
    <div class="card mb-5">
        <div class="card-header bg-dark text-white">
            Record a Return
        </div>
        <div class="card-body">
            <form action="{{ route('admin.sales.returns.store', $sale->id) }}" method="POST">
                @csrf
            
                <!-- Return Fields -->
                <div class="return-field row mb-3">
                    <div class="col-md-3">
                        <input type="number" name="returns[0][returned_amount]" class="form-control" placeholder="Returned Amount" required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="returns[0][description]" class="form-control" placeholder="Description" required>
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="returns[0][return_date]" class="form-control" placeholder="Return Date" required>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" name="action" value="record_return" class="btn btn-success">Record Return</button>
                    </div>
                </div>
            </form>
            
            <form action="{{ route('admin.sales.addDeduction', $sale->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="deducted_amount">Deducted Amount</label>
                    <input type="number" name="deducted_amount" id="deducted_amount" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="deduction_reason">Deduction Reason</label>
                    <input type="text" name="deduction_reason" id="deduction_reason" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Deduction</button>
            </form>
        </div>
    </div>

    <!-- Returns Table Section -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Sale Returns</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Returned Amount</th>
                        <th>Deducted Amount</th>
                        <th>Deduction Reason</th>
                        <th>Return Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->returns as $return)
                        <tr>
                            <td>{{ $return->description }}</td>
                            <td>₹{{ number_format($return->returned_amount, 2) }}</td>
                            <td>₹{{ number_format($return->deducted_amount ?? 0, 2) }}</td>
                            <td>{{ $return->deduction_description ?? 'N/A' }}</td>
                            <td>{{ $return->return_date?->format('d-m-Y') ?? 'N/A' }}</td>
                            <td>
                                <!-- Add action buttons here -->
                                <a href="#" class="btn btn-warning btn-sm">Edit</a>
                                <a href="#" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
