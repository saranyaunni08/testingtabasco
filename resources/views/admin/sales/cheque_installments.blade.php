@extends('layouts.default') {{-- Adjust this to your layout file --}}

@section('title', 'Cheque Installments Details')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-5">Sale Details for {{ $sale->customer_name }}</h2>
      

         <!-- Sale Summary Section -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header">Total Cash Received by Installments</div>
                    <div class="card-body">
                        <h5 class="card-title">₹{{ number_format($totalChequeinstallmentReceived, 2) }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-info mb-3">
                    <div class="card-header">Received Cheque Value</div>
                    <div class="card-body">
                        <h5 class="card-title">₹{{ number_format($recievedChequeValue, 2) }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-header">Total Received Cheque Value</div>
                    <div class="card-body">
                        <h5 class="card-title">₹{{ number_format($totalChequeValue, 2) }}</h5>
                    </div>
                </div>
            </div>
        
            {{-- <div class="col-md-4">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-header">Total Returned Amount</div>
                    <div class="card-body">
                        <h5 class="card-title">₹{{ number_format($totalReturnedAmount, 2) }}</h5>
                    </div>
                </div> --}}
        </div>

        <div class="card mb-5">
            <div class="card-header bg-dark text-white">
                Record a Return
            </div>
            <div class="card-body">
                <form action="{{ route('admin.sales.chequereturns.store', $sale->id) }}" method="POST">
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
       

        <div class="card-body">
        
            <h5 class="mt-4">Installment Details</h5>
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Amount Paid</th>
                        <th>Payment Date</th>
                        <th>Payment Method</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($installments as $index => $installment)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>₹{{ number_format($installment->total_paid, 2) }}</td>
                            <td>{{ $installment->payment_date }}</td>
                            <td>{{ ucfirst($installment->payment_method) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
