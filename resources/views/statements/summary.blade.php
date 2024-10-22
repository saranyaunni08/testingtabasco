@extends('layouts.default', ['title' => 'Summary Statement'])

@section('content')
<div class="container mt-5">
    <h2>Summary Statement</h2>
    <p><strong>Total Cash Installments:</strong> ₹{{ number_format($sale->cash_installment_amount, 2) }}</p>
    <p><strong>Total Cheque Installments:</strong> ₹{{ number_format($sale->cheque_installment_amount, 2) }}</p>
    <p><strong>Grand Total:</strong> ₹{{ number_format($sale->cash_installment_amount + $sale->cheque_installment_amount, 2) }}</p>
</div>
@endsection
