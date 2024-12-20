@extends('layouts.default')

@section('content')
<div class="container my-5">
    <h2 class="text-center mb-5">Edit Return for {{ $sale->customer_name }}</h2>

    <form action="{{ route('admin.sales.returns.update', [$sale->id, $return->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="returned_amount">Returned Amount</label>
            <input type="number" name="returned_amount" id="returned_amount" class="form-control" value="{{ old('returned_amount', $return->returned_amount) }}" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <input type="text" name="description" id="description" class="form-control" value="{{ old('description', $return->description) }}" required>
        </div>

        <div class="form-group">
            <label for="return_date">Return Date</label>
            <input type="date" name="return_date" id="return_date" class="form-control" value="{{ old('return_date', optional($return->return_date)->format('Y-m-d')) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Return</button>
    </form>
</div>
@endsection
