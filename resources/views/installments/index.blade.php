@extends('layouts.default')

@section('content')

<div class="container my-5">
  <div class="card shadow-sm p-4">
    <h2 class="text-center mb-4 text-primary">Installments for {{ $sale->customer_name }}</h2>
    <a href="{{ route('admin.installments.downloadFullPdf', $sale->id) }}" class="btn btn-success btn-sm ms-2">
      <i class="fas fa-download"></i> Download Full Page PDF
  </a>
  
    <p  class=" mb-4">Room number:{{ $sale->room->room_number }} , 
        Floor: {{$sale->room->room_floor }} ,
        Type: {{$sale->room->room_type}} </p>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped table-bordered table-hover">
      <thead class="table-info">
        <tr>
          <th>Installment Date</th>
          <th>Installment Amount (₹)</th>
          <th>Paid Amount (₹)</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($sale->installments as $installment)
          @php
              // Calculate the remaining amount dynamically
              $remainingAmount = $installment->installment_amount - ($installment->total_paid ?? 0);
          @endphp
          <tr>
            <td>{{ \Carbon\Carbon::parse($installment->installment_date)->format('d-m-Y') }}</td>
            <td>₹{{ number_format($installment->installment_amount, 2) }}</td>
            <td>₹{{ number_format($installment->total_paid ?? 0, 2) }}</td>
            <td>{{ ucfirst($installment->status) }}</td>
            <td>
              @if($installment->status !== 'Paid')
              <form action="{{ route('admin.installments.markPayment', $sale->id) }}" method="POST" onsubmit="return validatePaymentAmount({{ $remainingAmount }}, this)">
                @csrf
                <input type="hidden" name="installment_id" value="{{ $installment->id }}">

                <div class="mb-3">
                  <label for="paid_amount_{{ $installment->id }}" class="form-label">Enter Paid Amount:</label>
                  <input type="number" name="paid_amount" id="paid_amount_{{ $installment->id }}" class="form-control" placeholder="Enter paid amount" step="0.01" required oninput="checkAmount({{ $remainingAmount }}, this, 'amountError_{{ $installment->id }}')">
                  <small id="amountError_{{ $installment->id }}" class="text-danger" style="display:none;">The paid amount cannot exceed ₹{{ number_format($remainingAmount, 2) }}</small>
                </div>

                <div class="mb-3">
                  <label for="payment_date_{{ $installment->id }}" class="form-label">Payment Date:</label>
                  <input type="date" name="payment_date" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-success">Record Payment</button>
              </form>
              @else
               
                @if($sale->installments->isNotEmpty())
                <a href="{{ route('admin.installments.downloadPdf', $sale->id) }}" class="btn btn-success btn-sm ms-2">
                    <i class="fas fa-download" btn-success style="font-size: 24px; height: 30px; width: 30px;"></i> <!-- Custom size -->
                </a>
                @endif
            

                </span>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="text-center">No installments found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection

<script>
  // Check if the entered paid amount does not exceed the remaining amount
  function checkAmount(remainingAmount, inputElement, errorElementId) {
      const paidAmountInput = parseFloat(inputElement.value);
      const errorMessage = document.getElementById(errorElementId);

      if (!isNaN(paidAmountInput) && paidAmountInput > remainingAmount) {
          errorMessage.textContent = 'The paid amount cannot exceed ₹' + remainingAmount.toLocaleString('en-IN', { minimumFractionDigits: 2 });
          errorMessage.style.display = 'block';
      } else {
          errorMessage.style.display = 'none';
      }
  }

  // Validate the paid amount on form submission
  function validatePaymentAmount(remainingAmount, formElement) {
      const paidAmountInput = parseFloat(formElement.querySelector('input[name="paid_amount"]').value);
      
      if (paidAmountInput > remainingAmount) {
          alert('The paid amount cannot exceed ₹' + remainingAmount.toLocaleString('en-IN', { minimumFractionDigits: 2 }));
          return false;
      }
      return true;
  }
</script>

<style>
  body {
    background-color: #f7f7f7; /* Light background */
  }

  .container {
    max-width: 1200px;
  }

  .card {
    background-color: #fff; /* White background for the card */
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
    background-color: #17a2b8; /* Light Blue background */
    color: black;
  }

  .table-striped tbody tr:nth-child(odd) {
    background-color: #f9f9f9;
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

  .alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
  }

  .badge.bg-info {
    background-color: #17a2b8; /* Light Blue for "Paid" status */
  }

  .text-primary {
    color: #007bff;
  }
</style>
