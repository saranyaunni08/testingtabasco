@extends('layouts.default')

@section('content')
<div class="container my-5">
  <h2 class="text-center mb-5">Cash Installments for <span class="text-primary">{{ $sale->customer_name }}</span></h2>
  @if($sale->cashInstallments->isEmpty())
    <div class="alert alert-info text-center">No cash installments found for this sale.</div>
  @else
    <div class="row row-cols-1 row-cols-md-3 g-4">
      @foreach ($sale->cashInstallments as $cashInstallment)
        <div class="col">
          <div class="card h-100 shadow-sm border-0">
            <div class="card-body d-flex flex-column">
              <h3 class="card-title text-center mb-3"><b>{{ $cashInstallment->installment_name ?? 'No name provided' }}</b></h3>
              <p class="text-center mb-3">Amount: <b>{{ $cashInstallment->installment_amount }}</b></p>
              <p class="text-center mb-3">Paid: <b>{{ $cashInstallment->paid_amount }}</b></p>
              <p class="text-center mb-3">Status: <b>{{ $cashInstallment->status }}</b></p>
              <a href="{{ route('admin.cash_installments.show', ['saleId' => $sale->id]) }}" class="btn btn-secondary text-white mb-2">
                <i class="bi bi-card-checklist"></i> View Cash Installment Details
              </a>
              <button type="button" class="btn btn-danger text-white mt-auto" onclick="confirmCancelSale({{ $sale->id }})">
                <i class="bi bi-x-circle"></i> Cancel Sale
              </button>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @endif
</div>

<script>
  function confirmCancelSale(saleId) {
    if (confirm("Are you sure you want to cancel this sale?")) {
      // Redirect to the cancellation form page
      window.location.href = `/admin/sales/cancel/${saleId}`;
    }
  }
</script>
@endsection
