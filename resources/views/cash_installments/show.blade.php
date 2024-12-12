@extends('layouts.default')

@section('content')
<div class="container my-5">
  <div class="card shadow-sm p-4">
    <h2 class="text-center mb-4 text-primary">Cash Installments for {{ $sale->customer_name }}</h2>

    <p class="mb-4">Room number: {{ $sale->room->room_number }}, 
        Floor: {{ $sale->room->room_floor }}, 
        Type: {{ $sale->room->room_type }}</p>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($sale->cashInstallments->isEmpty())
      <div class="alert alert-info text-center">No cash installments found for this customer.</div>
    @else
    <table class="table">
        <thead>
            <tr>
                <th>Installment Date</th>
                <th>Installment Amount</th>
                <th>Paid Amount</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sale->cashInstallments as $installment)
                @php
                    // Calculate the remaining amount dynamically for each installment
                    $remainingAmount = $installment->installment_amount - ($installment->total_paid ?? 0);
                @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($installment->installment_date)->format('d-m-Y') }}</td>
                    <td>₹{{ number_format($installment->installment_amount, 2) }}</td>
                    <td>₹{{ number_format($installment->total_paid ?? 0, 2) }}</td>
                    <td>{{ ucfirst($installment->status) }}</td>
                    <td>
                        @if ($installment->status !== 'Paid')
                        <form action="{{ route('admin.cashInstallments.markPayment', ['sale' => $sale->id]) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="paid_amount_{{ $installment->id }}">Enter Paid Amount</label>
                                <input type="number" id="paid_amount_{{ $installment->id }}" name="paid_amount" class="form-control" step="0.01" required>
                                
                                <!-- Dynamically displayed error message if amount exceeds remaining balance -->
                                <span id="paid-amount-error-{{ $installment->id }}" class="text-danger" style="display: none;"></span>
                            </div>
                            
                            <div class="form-group">
                                <label for="payment_date_{{ $installment->id }}">Payment Date</label>
                                <input type="date" id="payment_date_{{ $installment->id }}" name="payment_date" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Payment</button>
                        </form>
                        
                        @else
                            <span class="text-success">Fully Paid</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif
  </div>
</div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Loop through each installment and handle the dynamic validation for each one
        @foreach ($sale->cashInstallments as $installment)
            const remainingAmount{{ $installment->id }} = {{ $installment->installment_amount - ($installment->total_paid ?? 0) }};
            const paidAmountInput{{ $installment->id }} = document.getElementById('paid_amount_{{ $installment->id }}');
            const errorMessage{{ $installment->id }} = document.getElementById('paid-amount-error-{{ $installment->id }}');

            paidAmountInput{{ $installment->id }}.addEventListener('input', function() {
                checkAmount(remainingAmount{{ $installment->id }}, paidAmountInput{{ $installment->id }}, 'paid-amount-error-{{ $installment->id }}');
            });
        @endforeach
    });

    function checkAmount(remainingAmount, input, errorId) {
        const errorElement = document.getElementById(errorId);
        const enteredAmount = parseFloat(input.value);

        // Show error if entered amount exceeds remaining amount
        if (enteredAmount > remainingAmount) {
            errorElement.textContent = `The paid amount cannot exceed ₹${remainingAmount.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
            errorElement.style.display = 'block';
        } else {
            errorElement.style.display = 'none';
        }
    }
</script>

<style>
  body {
    background-color: #f7f7f7;
  }

  .container {
    max-width: 1200px;
  }

  .card {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }

  .table {
    background-color: #fff;
    border-radius: 10px;
    margin-top: 20px;
  }

  .table th, .table td {
    padding: 12px;
    text-align: center;
  }

  .table th {
    background-color: #17a2b8;
    color: black;
  }

  .form-control {
    border-radius: 5px;
    padding: 10px;
  }

  .btn-success {
    padding: 8px 15px;
    font-size: 16px;
    border-radius: 5px;
  }
</style>
