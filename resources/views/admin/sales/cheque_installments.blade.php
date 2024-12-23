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
                        <h5 class="card-title">₹{{ number_format($totalReceivedAfterDeductions, 2) }}</h5>
                    </div>
                </div>
            </div>
        
            <div class="col-md-4">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-header">Total Returned Amount</div>
                    <div class="card-body">
                        <h5 class="card-title">₹{{ number_format($totalReturnedChequeAmount, 2) }}</h5>
                    </div>
                </div>
        </div>

          <!-- Deductions Section -->
    <div class="card mb-5">
        <div class="card-header bg-dark text-white">
            Cheque Deductions
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
                    @foreach($sale->chequeDeductions as $deduction) <!-- Reference $sale->cashDeductions -->
                        <tr>
                            <td>₹{{ number_format($deduction->cheque_deducted_amount, 2) }}</td>
                            <td>{{ $deduction->cheque_deduction_reason }}</td>
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
            <form action="{{ route('admin.sales.chequereturns.store', $sale->id) }}" method="POST">
                @csrf
            
                <!-- Return Fields -->
                <div class="return-field row mb-3">
                    <div class="col-md-4">
                        <input type="number" name="returns[0][returned_amount]" class="form-control" placeholder="Returned Amount" required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="returns[0][description]" class="form-control" placeholder="Description" required>
                    </div>
                    <div class="col-md-4">
                        <input type="date" name="returns[0][return_date]" id="return_date" class="form-control">
                    </div><br>
                    
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-success">Record Return</button>
                </div>
            </form>
            
            
            <form action="{{ route('admin.sales.addChequeDeduction', $sale->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="cheque_deducted_amount">Deducted Amount</label>
                    <input type="number" name="cheque_deducted_amount" id="cheque_deducted_amount" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="cheque_deduction_reason">Deduction Reason</label>
                    <input type="text" name="cheque_deduction_reason" id="cheque_deduction_reason" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Deduction</button>
            </form>
            
        </div>
    </div>
       

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
                        <th>Return Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->SalesChequeReturns as $return)
                        <tr>
                            <td>{{ $return->description }}</td>
                            <td>₹{{ number_format($return->returned_amount, 2) }}</td>
                            <td>{{ \Carbon\Carbon::parse($return->return_date)->format('d-m-Y') }}</td>
                            <td>
                                <a href="{{ route('admin.sales.returns.edit', [$sale->id, $return->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('admin.sales.returns.destroy', [$sale->id, $return->id]) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this return?');">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
