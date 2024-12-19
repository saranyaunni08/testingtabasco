
@extends('layouts.default', ['title' => 'Cancelled Sales'])

@section('content')
    <div class="container mt-5">
        <h2 class="display-6 fw-bold">Cancelled Sales</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif


        <!-- Display Cancelled Sales List -->
        <table class="table table-bordered shadow-lg">
            <thead class="bg-dark text-white text-center">
                <tr>
                    <th colspan="2">Customer Details Overview</th>
                    <!-- Cancel Button -->
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                        Cancel Sale
                    </button>

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
                                @foreach ($sale->installments as $installment) <!-- Assuming all records in installments belong to cheque -->
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($installment->installment_date)->format('d-m-Y') }}</td> <!-- Format date as needed -->
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
                    </td>
                </tr>
            </tbody>
            <!-- Cancel Modal -->
            <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="cancelForm" method="POST" action="{{ route('admin.sales.cancel', $sale->id) }}">
                            @csrf
                            @method('PATCH')
                            <div class="modal-header">
                                <h5 class="modal-title" id="cancelModalLabel">Cancel Sale</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="cancelDescription" class="form-label">Reason for Cancellation</label>
                                    <textarea name="cancel_description" id="cancelDescription" class="form-control" rows="4" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-danger">Submit Cancellation</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </table>
    </div>
@endsection
