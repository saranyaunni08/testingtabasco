@extends('layouts.default')

@section('content')
<div class="container my-5">
  <h2 class="text-center mb-5">Customers for <span class="text-primary">{{ $building->building_name }}</span></h2>

  @if($customers->isEmpty())
    <div class="alert alert-info text-center">No customers found for this building.</div>
  @else
    <div class="row row-cols-1 row-cols-md-3 g-4">
      @foreach ($customers as $customer)
        <div class="col">
          <div class="card h-100 shadow-sm border-0">
            <div class="card-body d-flex flex-column">
              <h3 class="card-title text-center mb-3"><b>{{ $customer->customer_name ?? 'No name provided' }}</b></h3>
              <a href="{{ route('admin.customer.info', ['saleId' => $customer->id]) }}" 
                 class="btn btn-primary text-white mb-2">
                <i class="bi bi-person"></i> View Customer Details
              </a>
              <a href="{{ route('admin.installments.show', ['saleId' => $customer->id]) }}" 
                 class="btn btn-secondary text-white mb-2">
                <i class="bi bi-card-checklist"></i> Cheque Installments
              </a>
              <a href="{{ route('admin.cash_installments.show', ['saleId' => $customer->id]) }}" 
                class="btn btn-secondary text-white mb-2">
                 <i class="bi bi-card-checklist"></i> Cash Installments
             </a>
             
              <a href="{{ route('admin.exchange.confirm', ['saleId' => $customer->id, 'building_id' => $building->id]) }}" 
                 class="btn btn-warning text-white mt-auto">
                <i class="bi bi-arrow-repeat"></i> Confirm Exchange
              </a>
              <button type="button" class="btn btn-danger text-white mt-auto" 
              onclick="confirmCancelSale({{ $customer->id }})">
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
