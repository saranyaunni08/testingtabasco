@extends('layouts.default', ['title' => 'Customer Details'])

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            @if ($sales->isNotEmpty())
                @foreach ($sales as $sale)
                    <h5 class="mb-0" style="text-transform:capitalize">{{ $sale->customer_name }}</h5>
                @endforeach
            @else
                <h5 class="mb-0">No sales data available</h5>
            @endif
        </div>
        <div class="card-body">
            @if ($sales->isNotEmpty())
                <div class="table-responsive">
                    <table id="customerDetailsTable" class="table table-bordered table-hover table-striped" style="width:100%">
                        <thead class="table-dark">
                            <tr>
                                <th>Building Name</th>
                                <th>Contact</th>
                                <th>Email</th>
                                <th>Room Model</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sales as $sale)
                                <tr>
                                    <td style="text-transform:capitalize;color:black;">{{ $sale->room->building->building_name }}</td>
                                    <td style="color:black;">{{ $sale->customer_contact }}</td>
                                    <td style="color:black;">{{ $sale->customer_email }}</td>
                                    <td style="color:black;">{{ $sale->room->room_type }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <div class="card mt-3">
                                            <div class="card-header">
                                                <strong style="text-transform: capitalize">{{ $sale->room->room_type }} Details</strong>
                                            </div>
                                            <div class="card-body">
                                                <table class="table table-sm table-borderless">
                                                    <tbody>
                                                        @if ($sale->room->room_type === 'Flat')
                                                            <tr><td><strong style="color:black;">Room Number:</strong></td><td style="color:black; text-transform: capitalize">{{ $sale->room->room_number }}</td></tr>
                                                            <tr><td><strong style=" color:black;">Flat Model:</strong></td><td style="color:black; ">{{ $sale->room->flat_model }}</td></tr>
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
                                                            <tr><td><strong style="color:black;">Total Amount:</strong></td><td style="color:black;">₹{{ $sale->total_amount }}</td></tr>
                                                            
                                                            <tr><td><strong style="color:black;">GST %:</strong></td><td style="color:black;">{{ $sale->gst_percent }}%</td></tr>
                                                            <tr><td><strong style="color:black;">GST Amount:</strong>
                                                                <td style="color:black;">₹{{ number_format($sale->total_with_gst - $sale->room_rate, 2) }}</td></tr>
                                                            <tr><td><strong style="color:black;">Total With GST:</strong></td><td style="color:black;">₹{{ $sale->total_with_gst }}</td></tr>

                                                            <tr><td><strong style="color:black;">Discount %:</strong></td><td style="color:black;">₹{{ $sale->discount_percent}}</td></tr>
                                                            <tr><td><strong style="color:black;">Discount Amount:</strong></td><td style="color:black;">₹{{$sale->total_with_gst - $sale->total_with_discount }}</td></tr>
                                                            <tr><td><strong style="color:black;">Parking Amount:</strong></td><td style="color:black;">₹{{ $sale->parking_amount }}</td></tr>
                                                            <tr>
                                                                <td><strong style="color:black;">No of Installments:</strong></td>
                                                                <td id="remaining-installments-{{ $sale->id }}" style="color:black;">{{ $sale->installments }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong style="color:black;">Installment Date:</strong></td>
                                                                <td id="remaining-installments-{{ $sale->id }}" style="color:black;">{{ $sale->installment_date }}</td>
                                                            </tr>
                                                            <tr><td><strong style="color:black;">Amount per Installment:</strong></td>
                                                            <td style="color:black;">₹{{ $sale->installments > 0 ? number_format($sale->remaining_balance / $sale->installments, 2) : 'N/A' }}</td></tr>
                                                            <tr style="display: none;">
                                                                <td><strong style="color:black;">Remaining Installments Total:</strong></td>
                                                                <td id="remaining-installments-total">{{ count($sales) }}</td>
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

                                                @foreach ($sales as $sale)
                                                <!-- Previous code omitted for brevity -->
                                            
                                                <!-- Installment Calendar -->
                                                <div class="table-responsive mt-4">
                                                    <h5 class="text-center">Installment Calendar</h5>
                                                    <table class="table table-bordered">
                                                        <thead class="table-dark">
                                                            <tr>
                                                                <th>Sl No</th>
                                                                <th>Installment Date</th>
                                                                <th>Installment Amount</th>
                                                                <th>Status</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $installmentDate = \Carbon\Carbon::createFromFormat('Y-m-d', $sale->installment_date);
                                                                $installmentAmount = number_format($sale->remaining_balance / $sale->installments, 2);
                                                            @endphp
                                                            @for ($i = 0; $i < $sale->installments; $i++)
                                                                <tr>
                                                                    <td>{{ $i + 1 }}</td>
                                                                    <td>{{ $installmentDate->format('d-m-Y') }}</td>
                                                                    <td>₹{{ $installmentAmount }}</td>
                                                                    <td>
                                                                        @if (isset($sale->installments_paid) && count($sale->installments_paid) > $i && $sale->installments_paid[$i])
                                                                            <span class="badge badge-success">Paid</span>
                                                                        @else
                                                                            <span class="badge badge-danger">Due</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if (!isset($sale->installments_paid) || count($sale->installments_paid) <= $i || !$sale->installments_paid[$i])
                                                                            <button type="button" class="btn btn-primary btn-sm mark-paid-btn" data-toggle="modal" data-target="#markPaidModal" 
                                                                                data-installment-date="{{ $installmentDate->format('d-m-Y') }}" data-installment-amount="₹{{ $installmentAmount }}"
                                                                                data-sale-id="{{ $sale->id }}" data-index="{{ $i }}">Mark as Paid</button>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                @php
                                                                    $installmentDate->addMonth();
                                                                @endphp
                                                            @endfor
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!-- End Installment Calendar -->
                                            
                                               
                                            @endforeach
                                            

                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Mark Paid Modal -->
<div class="modal fade" id="markPaidModal" tabindex="-1" role="dialog" aria-labelledby="markPaidModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="markPaidModalLabel">Mark Installment as Paid</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="markPaidForm" action="{{ route('admin.installments.markPaid', ['sale' => $sale->id]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="sale_id" value="{{ $sale->id }}">
                    
                    <div class="form-group">
                        <label for="modalInstallmentDate">Installment Date</label>
                        <input type="date" class="form-control" id="modalInstallmentDate" name="installment_date" value="{{ $sale->installment_date }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="modalInstallmentAmount">Installment Amount</label>
                        <input type="text" class="form-control" id="modalInstallmentAmount" name="installment_amount" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label for="modalTransactionDetails">Transaction Details</label>
                        <textarea class="form-control" id="modalTransactionDetails" name="transaction_details" rows="3"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="modalBankDetails">Bank Details</label>
                        <textarea class="form-control" id="modalBankDetails" name="bank_details" rows="3"></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-success">Mark as Paid</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
   $(document).ready(function() {
    // When the markPaidForm is submitted
    $('#markPaidForm').on('submit', function(e) {
        e.preventDefault(); // Prevent normal form submission

        var form = $(this); // Get the form element
        var actionUrl = form.attr('action'); // Get the form action URL
        var formData = form.serialize(); // Serialize form data

        // Make an AJAX POST request
        $.ajax({
            type: 'POST',
            url: actionUrl,
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Add CSRF token
            },
            success: function(response) {
                // Handle success response
                $('#markPaidModal').modal('hide'); // Close the modal
                alert('Installment marked as paid successfully.'); // Display success message

                // Update UI: Find the installment row and mark it as Paid
                var index = $('#modalIndex').val(); // Get the index of the installment
                var installmentRow = $('#customerDetailsTable tbody tr').eq(index); // Find the corresponding row
                installmentRow.find('td:nth-child(4)').html('<span class="badge badge-success">Paid</span>'); // Update status cell

                // Optionally, update other UI elements if needed

            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText); // Log error message
                alert('An error occurred. Please try again.'); // Display error message
            }
        });
    });
});

</script>
@endsection