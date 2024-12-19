@extends('layouts.default')

@section('content')
<div class="container my-5" style="background-color: #f8f9fa; padding: 20px; border-radius: 8px;">
  <h2 class="text-center mb-5">Customers for <span class="text-primary">{{ $building->building_name }}</span></h2>
  
  <!-- Search Form -->
  <form method="GET" action="{{ route('admin.building.customers', ['buildingId' => $building->id]) }}" class="mb-4">
    <div class="row justify-content-center">
      <div class="col-md-4">
        <input type="text" name="search" class="form-control form-control-lg" placeholder="Search by name or room type" value="{{ request()->query('search') }}">
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-primary btn-lg w-100">Search</button>
      </div>
    </div>
  </form>

  @if($customers->isEmpty())
    <div class="alert alert-info text-center">No customers found for this building.</div>
  @else
    <div class="table-responsive">
      <table class="table table-striped table-bordered" style="background-color: #ffffff;">
        <thead style="background-color: #f1f1f1;">
          <tr>
            <th>Customer Name & Room Type</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($customers as $customer)
            <tr>
              <td> 
                <b>{{ strtoupper($customer->customer_name ?? 'No name provided') }} &nbsp;&nbsp; {{ strtoupper($customer->room->room_type ?? 'No name provided') }}</b>
              </td>
              <td class="text-center">
                <a href="{{ route('admin.customer.info', ['saleId' => $customer->id]) }}" 
                   class="btn btn-primary btn-sm">
                   <i class="bi bi-person"></i> View Details
                </a>
                <a href="{{ route('admin.installments.show', ['saleId' => $customer->id]) }}" 
                   class="btn btn-info btn-sm">
                   <i class="bi bi-card-checklist"></i> Cheque Installments
                </a>
                <a href="{{ route('admin.cash_installments.show', ['saleId' => $customer->id]) }}" 
                   class="btn btn-success btn-sm">
                   <i class="bi bi-card-checklist"></i> Cash Installments
                </a>
                <a href="{{ route('admin.exchange.confirm', ['saleId' => $customer->id, 'building_id' => $building->id]) }}" 
                   class="btn btn-warning btn-sm">
                   <i class="bi bi-arrow-repeat"></i> Confirm Exchange
                </a>
                @if ($customer->status === 'cancelled')
                <!-- View Cancelled Sale Details -->
                <a href="{{ route('admin.sales.list_cancelled_details', ['id' => $customer->id]) }}" class="btn btn-danger btn-sm">
                  <i class="bi bi-x-circle"></i> View Cancelled Details
              </a>
              <a href="{{ route('admin.sales.returndetails', ['saleId' => $customer->id]) }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left-circle"></i> Return
            </a>
            
              
              
            @else
                <!-- Cancel Sale Button -->
                <button type="button" class="btn btn-danger btn-sm" 
                  onclick="confirmCancelSale({{ $customer->id }})">
                  <i class="bi bi-x-circle"></i> Cancel Sale
                </button>

            @endif
            
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif
</div>

<script>

function confirmCancelSale(saleId) {
    if (confirm("Are you sure you want to cancel this sale?")) {
        window.location.href = `{{ url('admin/sales/details') }}/${saleId}`;
    }
}

</script>

@endsection
