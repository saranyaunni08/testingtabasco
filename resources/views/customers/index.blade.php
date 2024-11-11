@extends('layouts.default')

@section('content')

<div class="container my-4">
  <h2 class="text-center mb-4">Customers for {{ $building->building_name }}</h2>

  <div class="row">
    @forelse ($customers as $customer)
      <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0">
          <div class="card-body">
            <h5 class="card-title">{{ $customer->customer_name ?? 'No name provided' }}</h5>
            <a href="{{ route('admin.customer.info', ['saleId' => $customer->id]) }}" class="btn btn-primary text-white w-100" style="text-decoration: none;">
              View Customer Details
            </a>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12">
        <div class="alert alert-info text-center">No customers found for this building.</div>
      </div>
    @endforelse
  </div>
</div>

@endsection
