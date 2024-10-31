@extends('layouts.default')

@section('content')

<div class="container">
  <h1>Customers for {{ $building->builidng_name }}</h1>

  <ul class="list-group">
    @forelse ($customers as $customer)
        @if ($customer->sale_id)
            <li>
                <a href="{{ route('admin.customers.show', ['saleId' => $customer->sale_id]) }}">
                    {{ $customer->name }}
                </a>
            </li>
        @else
            <li class="list-group-item">No sale associated with {{ $customer->name }}.</li>
        @endif
    @empty
    @endforelse
  </ul>
</div>

@endsection
