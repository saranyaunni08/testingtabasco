@extends('layouts.default', ['title' => 'Customer Details'])

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            @foreach($sales as $sale)
            <h5 class="mb-0" style="text-transform:capitalize">{{ $sale->customer_name }}</h5>
            @endforeach
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="customerDetailsTable" class="table table-bordered table-hover table-striped" style="width:100%">
                    <thead class="table-dark">
                        <tr>
                            <th>Building Name</th>
                            <th>Contact</th>
                            <th>Email</th>
                            <th>Room Model</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $sale)
                        <tr>
                            <td style="text-transform:capitalize;color:black;">{{ $sale->room->building->building_name }}</td>
                            <td style="color:black;">{{ $sale->customer_contact }}</td>
                            <td style="color:black;">{{ $sale->customer_email }}</td>
                            <td style="color:black;">{{ $sale->room->room_type }}</td>
                            <td>
                                <table class="table table-sm table-borderless">
                                    <tbody>
                                        @if ($sale->room->room_type === 'Flat')
                                        <tr><td><strong style="color:black;">Room Number:</strong></td><td style="color:black; text-transform: capitalize">{{ $sale->room->room_number }}</td></tr>
                                        <tr><td><strong style="color:black;">Flat Model:</strong></td><td style="color:black;">{{ $sale->room->flat_model }}</td></tr>

                                            @if ($sale->area_calculation_type === 'carpet_area_rate')
                                            <tr><td><strong style="color:black;">Carpet Area sq ft:</strong></td><td style="color:black;">{{ $sale->room->flat_carpet_area }} sq ft</td></tr>
                                            <tr><td><strong style="color:black;">Carpet Area Price per sq ft:</strong></td><td style="color:black;">₹{{ $sale->room->flat_carpet_area_price }}</td></tr>
                                            <tr><td><strong style="color:black;">Expected Amount:</strong></td><td style="color:black;">₹{{ $sale->room->flat_expected_carpet_area_price }}</td></tr>
                                            @elseif ($sale->area_calculation_type === 'built_up_area_rate')
                                            <tr><td><strong style="color:black;">Super Build Up sq ft:</strong></td><td style="color:black;">{{ $sale->room->flat_build_up_area }}</td></tr>
                                            <tr><td><strong style="color:black;">Super Build Up Price per sq ft:</strong></td><td style="color:black;">₹{{ $sale->room->flat_super_build_up_price }}</td></tr>
                                            <tr><td><strong style="color:black;">Expected Amount:</strong></td><td style="color:black;">₹{{ $sale->room->flat_expected_super_buildup_area_price }}</td></tr>
                                            @endif
                                        <tr><td><strong style="color:black;">Sale Amount:</strong></td><td style="color:black;">₹{{ $sale->sale_amount }}</td></tr>
                                        <tr><td><strong style="color:black;">Gst % :</strong></td><td style="color:black;">₹{{ $sale->gst_percent }}</td></tr>
                                        <tr><td><strong style="color:black;">GST Amount:</strong></td>
                                        <td style="color:black;">₹{{ number_format($sale->total_with_gst - $sale->room_rate, 2) }}</td></tr>
                                        <tr><td><strong style="color:black;">Parking Amount :</strong></td><td style="color:black;">₹{{ $sale->parking_amount }}</td></tr>
                                        <tr>
                                            <td><strong style="color:black;">No of Installments :</strong></td>
                                            <td id="remaining-installments-{{ $sale->id }}" style="color:black;">{{ $sale->installments }}</td>
                                        </tr>
                                        <tr><td><strong style="color:black;">Amount per Installment :</strong></td>
                                        <td style="color:black;">₹{{ $sale->installments > 0 ? number_format($sale->remaining_balance / $sale->installments, 2) : 'N/A' }}</td></tr>
                                        <tr style="display: none;">
                                            <td><strong style="color:black;">Remaining Installments Total :</strong></td>
                                            <td id="remaining-installments-total">{{ count($sales) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <form id="markPaidForm-{{ $sale->id }}" action="{{ route('admin.installments.markPaid', $sale->id) }}" method="POST" class="mark-paid-form">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="amount_paid">Amount Paid:</label>
                                                        <input type="number" name="amount_paid" class="form-control" placeholder="Amount paid" required>
                                                        <input type="hidden" name="installment_number" id="installment_number-{{ $sale->id }}" value="1">
                                                    </div>
                                                    <button type="submit" class="btn btn-primary mt-2">Mark as Paid</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @elseif ($sale->room->room_type === 'Shops')
                                        <!-- Shop details -->
                                        @elseif ($sale->room->room_type === 'Table space')
                                        <!-- Table space details -->
                                        @elseif ($sale->room->room_type === 'Chair space')
                                        <!-- Chair space details -->
                                        @endif
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table td table {
        margin-bottom: 0;
    }
    .table td table td {
        padding: 0.25rem;
    }
    .table td, .table th, .table td table td, .table td table th {
        color: black !important;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    $('.mark-paid-form').on('submit', function(e) {
        e.preventDefault();

        var form = $(this);
        var saleId = form.attr('id').split('-')[1]; // Get the sale ID from form ID
        var url = form.attr('action');
        var amountPaid = form.find('input[name="amount_paid"]').val();
        var installmentNumber = parseInt(form.find('input[name="installment_number"]').val());

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                amount_paid: amountPaid,
                installment_number: installmentNumber
            },
            success: function(response) {
                if (response.success) {
                    alert('Installment marked as paid.');

                    // Update remaining installments display
                    var remainingInstallments = response.remaining_installments;
                    $('#remaining-installments-' + saleId).text(remainingInstallments);

                    // Update remaining balance display
                    var remainingBalance = response.remaining_balance;
                    // Update remaining balance display here if necessary

                    // Decrease the total number of installments shown
                    var totalInstallments = parseInt($('#remaining-installments-total').text());
                    $('#remaining-installments-total').text(totalInstallments - 1);

                    // Hide the form if no remaining installments
                    if (remainingInstallments === 0) {
                        form.closest('tr').hide(); // Hide form or handle completion UI
                    }

                    // Update form for next installment
                    var nextInstallmentNumber = installmentNumber + 1;
                    form.find('input[name="installment_number"]').val(nextInstallmentNumber);

                    // Optionally refresh the page or update UI as needed
                    // location.reload(); // Uncomment if needed to refresh the page
                } else {
                    alert('Error marking installment as paid.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error marking installment as paid:', error);
                alert('Error marking installment as paid. Please try again.');
            }
        });
    });
});
</script>
@endpush
