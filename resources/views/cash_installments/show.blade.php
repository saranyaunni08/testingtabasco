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
                <th>Remaining Balance</th> 
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
              
          @error('paid_amount')
            <span class="text-danger">{{ $message }}</span>
          @enderror
          @foreach ($sale->cashInstallments as $installment)
            @php
                $remainingAmount = $installment->installment_amount - ($installment->total_paid ?? 0);
            @endphp
            <tr>
                <td>{{ \Carbon\Carbon::parse($installment->installment_date)->format('d-m-Y') }}</td>
                <td>₹{{ number_format($installment->installment_amount, 2) }}</td>
                <td>₹{{ number_format($installment->total_paid ?? 0, 2) }}</td>
                <td>₹{{ number_format($remainingAmount, 2) }}</td> 
                <td>{{ ucfirst($installment->status) }}</td>
                <td>
                    @if ($installment->status !== 'Paid')
                        <form id="payment-form-{{ $installment->id }}" action="{{ route('admin.cashInstallments.markPayment', ['sale' => $sale->id]) }}" method="POST">
                            @csrf
                            <input type="hidden" name="installment_id" value="{{ $installment->id }}">
                            
                            <div class="form-group">
                                <label for="paid_amount_{{ $installment->id }}">Enter Paid Amount</label>
                                <input type="number" id="paid_amount_{{ $installment->id }}" name="paid_amount" class="form-control" step="0.01" required>
                            
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
    @foreach ($sale->cashInstallments as $installment)
        const remainingAmount{{ $installment->id }} = {{ $installment->installment_amount - ($installment->total_paid ?? 0) }};
        const form{{ $installment->id }} = document.getElementById('payment-form-{{ $installment->id }}');
        const paidAmountInput{{ $installment->id }} = document.getElementById('paid_amount_{{ $installment->id }}');
        const errorMessage{{ $installment->id }} = document.getElementById('paid-amount-error-{{ $installment->id }}');

        form{{ $installment->id }}.addEventListener('submit', function(event) {
            const enteredAmount = parseFloat(paidAmountInput{{ $installment->id }}.value);

            // Check if the paid amount exceeds the remaining amount
            if (enteredAmount > remainingAmount{{ $installment->id }}) {
                event.preventDefault(); // Prevent form submission
                errorMessage{{ $installment->id }}.textContent = `The paid amount cannot exceed ₹${remainingAmount{{ $installment->id }}.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                errorMessage{{ $installment->id }}.style.display = 'block'; // Show the error message
                paidAmountInput{{ $installment->id }}.classList.add('is-invalid'); // Add invalid class
            }
        });
    @endforeach
});
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
