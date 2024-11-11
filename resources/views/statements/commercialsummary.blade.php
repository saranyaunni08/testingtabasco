@extends('layouts.default')

@section('content')
<div class="container mt-5" style="background-color: #f9f9f9; border-radius: 10px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); padding: 20px;">
    <div class="text-center mb-4">
        <h2 class="font-weight-bold" style="color: #333;">Commercial Sales Summary</h2>
    </div>

    <table class="table table-striped table-bordered" style="background-color: white;">
        <thead class="thead-dark">
            <tr>
                <th style="width: 70%;">Description</th>
                <th style="width: 30%;" class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Total Sales Amount</strong></td>
                <td class="text-right">{{ number_format($totalSalesAmount, 2) }}</td>
            </tr>
          
            <tr>
                <td><strong>Total Sqft</strong></td>
                {{-- <td class="text-right">{{ number_format($totalSqft, 2) }} sqft</td> --}}
            </tr>
        </tbody>
    </table>

    <div class="mt-4">
        <h4 class="font-weight-bold" style="color: #333;">Sales Details by Floor</h4>
        @foreach($groupedSalesByFloor as $floor => $sales)
            <h5>Floor {{ $floor }}</h5>
            <ul class="list-group mb-3">
               {{-- @foreach($sales as $sale)
    Sale ID: {{ $sale->id }}, Amount: {{ number_format($sale->amount, 2) }}, GST: {{ number_format($sale->gst, 2) }}
@endforeach --}}

            </ul>
        @endforeach
    </div>
</div>
@endsection
