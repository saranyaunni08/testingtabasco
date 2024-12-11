@extends('layouts.default', ['title' => 'Confirm Exchange'])

@section('content')
<div class="container mt-5">
    <div class="text-center mb-4">
        <h2 class="display-6 fw-bold">Confirm Exchange for Customer: {{ $sale->customer_name }}</h2>
    </div>
    <div class="text-center mt-4">
        <a href="{{ route('admin.exchange.availability', ['building_id' => $building->id, 'sale_id' => $sale->id]) }}" class="btn btn-primary">Finalize Exchange</a>
    </div>
    <table class="table table-bordered shadow-lg">
        <thead class="bg-dark text-white text-center">
            <tr>
                <th colspan="2">Customer Details Overview</th>
            </tr>
        </thead>

        <tbody>
            <!-- Customer Information -->
            <tr>
                <td colspan="2" class="bg-primary text-white fw-bold">Customer Information</td>
            </tr>
            <tr>
                <td>
                    <p><strong>Customer Name:</strong> {{ $sale->customer_name }}</p>
                    <p><strong>Type:</strong> {{ $room->room_type }}</p>
                    <p><strong>Floor:</strong> {{ $room->room_floor }}</p>
                </td>
                <td>
                    <p><strong>Rooms:</strong> {{ $roomNumbers }}</p>
                    <p><strong>Sqft:</strong> 
                        {{ $sale->area_calculation_type === 'build_up_area' ? $sale->build_up_area : ($sale->area_calculation_type === 'carpet_area' ? $sale->carpet_area : 'N/A') }} sqft
                    </p>
                </td>
            </tr>

            <!-- Pricing Details -->
            <tr>
                <td colspan="2" class="bg-success text-white fw-bold">Pricing Details</td>
            </tr>
            <tr>
                <td>
                    <p><strong>Rate Per Sqft:</strong> ₹{{ number_format($sale->sale_amount, 2) }}</p>
                    <p><strong>Total Amount:</strong> ₹{{ number_format($sale->total_amount, 2) }}</p>
                    <p><strong>Exchange (Land):</strong> {{ $sale->exchange_land }}</p>
                    <p><strong>Balance:</strong> ₹{{ number_format($sale->balance, 2) }}</p>
                    <p><strong>Cash:</strong> ₹{{ number_format($sale->cash_value_amount, 2) }}</p>
                    <p><strong>Cheque:</strong> ₹{{ number_format($sale->total_cheque_value, 2) }}</p>
                    <p><strong>Discount:</strong> ₹{{ $sale->discount_amount }}</p>
                    <p><strong>Total After Discount:</strong> ₹{{ number_format($sale->final_amount, 2) }}</p>
                </td>
                <td>
                    <p><strong>Cash (with Additional):</strong> ₹{{ number_format($sale->total_cash_value, 2) }}</p>
                    <p><strong>Cheque (with Additional):</strong> ₹{{ number_format($sale->total_cheque_value_with_additional, 2) }}</p>
                    <p><strong>Additional Work (cash):</strong> ₹{{ $totalCashExpenses }}</p>
                    <p><strong>Additional Work (cheque):</strong> ₹{{ $sale->cheque_expense_amounts }}</p>
                    <p><strong>Additional Work:</strong> ₹{{ $additionalWork }}</p>
                    <p><strong>Total:</strong> ₹{{ number_format($totalWithAdditional, 2) }}</p>
                </td>
            </tr>

            <!-- GST Details -->
            <tr>
                <td colspan="2" class="bg-warning text-dark fw-bold">GST and Total Amount</td>
            </tr>
            <tr>
                <td colspan="2">
                    <p><strong>GST ({{$sale->gst_percentage}}%):</strong> ₹{{ number_format($sale->gst_amount, 2) }}</p>
                    <p><strong>Total Amount (with GST):</strong> ₹{{ number_format($sale->total_cheque_value_with_gst, 2) }}</p>
                </td>
            </tr>

            <!-- Installments Section -->
            <tr>
                <td class="bg-info text-white fw-bold">Cheque Installments</td>
                <td class="bg-info text-white fw-bold">Cash Installments</td>
            </tr>
            <tr>
                <td>
                    <p><strong>Number of Installments:</strong> {{ $sale->no_of_installments }}</p>
                    <p><strong>Installment Amount:</strong> ₹{{ number_format($sale->cheque_installment_amount, 2) }}</p>
                    <table class="table table-hover">
                        <thead class="table-secondary">
                            <tr>
                                <th>Date</th>
                                <th>Amount (₹)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sale->installments as $installment) 
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($installment->installment_date)->format('d-m-Y') }}</td> 
                                    <td>₹{{ number_format($installment->installment_amount, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
                <td>
                    <p><strong>Number of Installments:</strong> {{ $sale->cash_no_of_installments }}</p>
                    <p><strong>Installment Amount:</strong> ₹{{ number_format($sale->cash_installment_amount, 2) }}</p>
                    <table class="table table-hover">
                        <thead class="table-secondary">
                            <tr>
                                <th>Date</th>
                                <th>Amount (₹)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sale->cash_installments as $cash_installment)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($cash_installment->installment_date)->format('d-m-Y') }}</td>
                                    <td>₹{{ number_format($cash_installment->installment_amount, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>

            <!-- Summary -->
            <tr>
                <td colspan="2" class="bg-dark text-white fw-bold text-center">Summary</td>
            </tr>
            <tr>
                <td colspan="2" class="text-center">
                    <p><strong>Total (Cheque):</strong> ₹{{ number_format($totalChequeAmount, 2) }}</p>
                    <p><strong>Cash Total:</strong> ₹{{ number_format($totalCashAmount, 2) }}</p>
                    <p><strong>Balance Remaining:</strong> ₹{{ number_format($sale->remaining_balance, 2) }}</p>
                    <p><strong>Pending Work:</strong> ₹{{ $additionalWork }}</p>
                </td>
            </tr>
        </tbody>
    </table>

   
</div>
@endsection
