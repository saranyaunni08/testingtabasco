@extends('layouts.default', ['title' => 'Kiosks'])

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <h5 class="card-header">Kiosks Table</h5>
        <div class="card-body">
            <div class="table-responsive">
                <table id="kiosksTable" class="table table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <td>Room Floor</td>
                            <th>Kiosk Name</th>
                            <th>Kiosk Type</th>
                            <th>Kiosk Area</th>
                            <th>Kiosk Rate</th>
                            <th>Kiosk Expected Rate</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rooms as $room)
                        @if ($room->room_type == 'Kiosk')
                        <tr>
                            <td>{{ $room->room_floor }}</td>
                            <td>{{ $room->kiosk_name }}</td>
                            <td>{{ $room->kiosk_type }}</td>
                            <td>{{ $room->kiosk_area }} sq ft</td>
                            <td>₹{{ number_format($room->kiosk_rate, 2) }}</td>
                            <td>₹{{ number_format($room->kiosk_expected_rate, 2) }}</td>
                            <td>
                                <a href="{{ route('admin.rooms.edit', $room->id) }}" class="btn btn-success btn-sm">
                                    <i class="bx bx-edit bx-sm"></i>
                                </a>
                                <form action="{{ route('admin.rooms.destroy', ['building_id' => $room->building_id, 'room_id' => $room->id]) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt bx-sm"></i>
                                    </button>
                                </form>
                                @if ($room->status === 'available')
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#sellModal{{ $room->id }}">
                                    Sell Room
                                </button>
                                @else
                                <a href="{{ route('admin.customers.show', ['customerName' => $room->sale->customer_name]) }}"
                                    style="color: #28a745; font-weight: bold; font-size: 1.2em; border: 2px solid #28a745;
                                    padding: 5px 10px; border-radius: 5px; background-color: #e9f7ef; text-decoration:none;">View
                                </a>
                                @endif

                                <!-- Modal -->
                                <div class="modal fade" id="sellModal{{ $room->id }}" tabindex="-1" aria-labelledby="sellModalLabel{{ $room->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg"> <!-- Increased width -->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="sellModalLabel{{ $room->id }}">Sell Room {{ $room->name }}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('admin.sales.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="room_id" value="{{ $room->id }}">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label class="font-weight-bold" for="customer_name">Customer Name</label>
                                                                <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label class="font-weight-bold" for="customer_email">Customer Email</label>
                                                                <input type="email" class="form-control" id="customer_email" name="customer_email" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label class="font-weight-bold" for="customer_contact">Customer Contact</label>
                                                                <input type="text" class="form-control" id="customer_contact" name="customer_contact" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label class="font-weight-bold" for="sale_amount">Sale Amount in sq</label>
                                                                <input type="number" class="form-control" id="sale_amount" name="sale_amount" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label class="font-weight-bold" for="area_calculation_type">Area Calculation Type</label>
                                                                <select class="form-control" id="area_calculation_type" name="area_calculation_type" required>
                                                                    <option value="" selected disabled>Select</option>
                                                                    <option value="carpet_area_rate">Carpet Area Rate</option>
                                                                    <option value="built_up_area_rate">Super Built-up Area Rate</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label class="font-weight-bold" for="calculation_type">Calculation Type for Parking</label>
                                                                <select class="form-control" id="calculation_type" name="calculation_type" required>
                                                                    <option value="fixed_amount">Unparked</option>
                                                                    <option value="rate_per_sq_ft">Rate per sq ft</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group d-none" id="parking_rate_per_sq_ft_group{{ $room->id }}">
                                                                <label class="font-weight-bold" for="parking_rate_per_sq_ft">Parking Rate (per sq ft)</label>
                                                                <input type="number" class="form-control" id="parking_rate_per_sq_ft" name="parking_rate_per_sq_ft">
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group d-none" id="total_sq_ft_group{{ $room->id }}">
                                                                <label class="font-weight-bold" for="total_sq_ft_for_parking">Total Square Feet</label>
                                                                <input type="number" class="form-control" id="total_sq_ft_for_parking" name="total_sq_ft_for_parking">
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label class="font-weight-bold" for="discount_percent">Discount (%)</label>
                                                                <input type="number" step="0.01" class="form-control" id="discount_percent" name="discount_percent">
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label class="font-weight-bold" for="gst_percent">GST Percent</label>
                                                                <input type="number" step="0.01" class="form-control" id="gst_percent" name="gst_percent" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label class="font-weight-bold" for="cash_in_hand_percent">Cash in Hand %</label>
                                                                <input type="number" step="0.01" class="form-control" id="cash_in_hand_percent{{ $room->id }}" name="cash_in_hand_percent" oninput="calculateInHandAmount({{ $room->id }})">
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label class="font-weight-bold" for="in_hand_amount">In Hand Amount</label>
                                                                <input type="number" step="0.01" class="form-control" id="in_hand_amount{{ $room->id }}" name="in_hand_amount" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label class="font-weight-bold" for="advance_payment">Total Advance Payment</label>
                                                                <select class="form-control" id="advance_payment" name="advance_payment" required>
                                                                    <option value="now">Paying Now</option>
                                                                    <option value="later">Paying Later</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group d-none" id="advance_amount_group">
                                                                <label class="font-weight-bold" for="advance_amount">Advance Amount</label>
                                                                <input type="number" class="form-control" id="advance_amount" name="advance_amount">
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group d-none" id="last_date_group">
                                                                <label class="font-weight-bold" for="last_date">Last Date for Advance Payment</label>
                                                                <input type="date" class="form-control" id="last_date" name="last_date">
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label class="font-weight-bold" for="installments">Number of Installments</label>
                                                                <input type="number" class="form-control" id="installments" name="installments" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group d-none" id="payment_method_group">
                                                                <label class="font-weight-bold" for="payment_method">Payment Method</label>
                                                                <select class="form-control" id="payment_method" name="payment_method">
                                                                    <option value="cash">Cash</option>
                                                                    <option value="bank_transfer">Bank Transfer</option>
                                                                    <option value="cheque">Cheque</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group d-none" id="transfer_id_group">
                                                                <label class="font-weight-bold" for="transfer_id">Bank Transfer ID</label>
                                                                <input type="text" class="form-control" id="transfer_id" name="transfer_id">
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group d-none" id="cheque_id_group">
                                                                <label class="font-weight-bold" for="cheque_id">Cheque ID</label>
                                                                <input type="text" class="form-control" id="cheque_id" name="cheque_id">
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-12">
                                                            <h4>Total amount: ₹<span id="total"></span></h4>
                                                        </div>
                                                        <div class="col-12">
                                                            <h4>Remaining Balance: ₹<span id="remaining_balance"></span></h4>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Sell Room</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Modal -->
                            </td>
                        </tr>
                        @endif
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No kiosks available.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modalElements = document.querySelectorAll('[id^="sellModal"]');

        modalElements.forEach((modalElement) => {
            const roomId = modalElement.id.replace('sellModal', '');
            const calculationTypeSelect = modalElement.querySelector('#calculation_type');
            const areaCalculationTypeSelect = modalElement.querySelector('#area_calculation_type');
            const parkingRatePerSqFtGroup = modalElement.querySelector(`#parking_rate_per_sq_ft_group${roomId}`);
            const totalSqFtGroup = modalElement.querySelector(`#total_sq_ft_group${roomId}`);
            const advancePaymentSelect = modalElement.querySelector('#advance_payment');
            const advanceAmountGroup = modalElement.querySelector('#advance_amount_group');
            const paymentMethodGroup = modalElement.querySelector('#payment_method_group');
            const paymentMethodSelect = modalElement.querySelector('#payment_method');
            const transferIdGroup = modalElement.querySelector('#transfer_id_group');
            const chequeIdGroup = modalElement.querySelector('#cheque_id_group');
            const lastDateGroup = modalElement.querySelector('#last_date_group');
            const saleInput = modalElement.querySelector('#sale_amount');
            const totalElement = modalElement.querySelector('#total');
            const parkingRateInput = modalElement.querySelector('#parking_rate_per_sq_ft');
            const totalSqFtInput = modalElement.querySelector('#total_sq_ft_for_parking');
            const gstInput = modalElement.querySelector('#gst_percent');
            const discountInput = modalElement.querySelector('#discount_percent');
            const advanceAmountInput = modalElement.querySelector('#advance_amount');
            const remainingBalanceElement = modalElement.querySelector('#remaining_balance');
            const cashInHandPercentInput = modalElement.querySelector(`#cash_in_hand_percent${roomId}`);
            const inHandAmountInput = modalElement.querySelector(`#in_hand_amount${roomId}`);

            const flatFields = modalElement.querySelector('#flat_fields');
            const shopFields = modalElement.querySelector('#shop_fields');
            const tableSpaceFields = modalElement.querySelector('#table_space_fields');
            const kioskFields = modalElement.querySelector('#kiosk_fields');
            const chairSpaceFields = modalElement.querySelector('#chair_space_fields');

            function showRelevantAreaFields(roomType) {
                hideAllFields();
                if (roomType === 'Flat' && flatFields) {
                    flatFields.classList.remove('d-none');
                } else if (roomType === 'Shop' && shopFields) {
                    shopFields.classList.remove('d-none');
                } else if (roomType === 'Table Space' && tableSpaceFields) {
                    tableSpaceFields.classList.remove('d-none');
                } else if (roomType === 'Kiosk' && kioskFields) {
                    kioskFields.classList.remove('d-none');
                } else if (roomType === 'Chair Space' && chairSpaceFields) {
                    chairSpaceFields.classList.remove('d-none');
                }
            }

            function hideAllFields() {
                if (flatFields) flatFields.classList.add('d-none');
                if (shopFields) shopFields.classList.add('d-none');
                if (tableSpaceFields) tableSpaceFields.classList.add('d-none');
                if (kioskFields) kioskFields.classList.add('d-none');
                if (chairSpaceFields) chairSpaceFields.classList.add('d-none');
            }

            function toggleAdvancePaymentFields() {
    if (advancePaymentSelect && advancePaymentSelect.value === 'now') {
        if (advanceAmountGroup) advanceAmountGroup.classList.remove('d-none');
        if (paymentMethodGroup) paymentMethodGroup.classList.remove('d-none');
        if (lastDateGroup) lastDateGroup.classList.add('d-none'); // Ensure lastDateGroup is hidden when paying now
    } else if (advancePaymentSelect && advancePaymentSelect.value === 'later') {
        if (advanceAmountGroup) advanceAmountGroup.classList.add('d-none');
        if (paymentMethodGroup) paymentMethodGroup.classList.add('d-none');
        if (transferIdGroup) transferIdGroup.classList.add('d-none');
        if (chequeIdGroup) chequeIdGroup.classList.add('d-none');
        if (lastDateGroup) lastDateGroup.classList.remove('d-none'); // Show lastDateGroup when paying later
    }
}



            function togglePaymentMethodFields() {
                if (paymentMethodSelect && paymentMethodSelect.value === 'bank_transfer') {
                    if (transferIdGroup) transferIdGroup.classList.remove('d-none');
                    if (chequeIdGroup) chequeIdGroup.classList.add('d-none');
                } else if (paymentMethodSelect && paymentMethodSelect.value === 'cheque') {
                    if (transferIdGroup) transferIdGroup.classList.add('d-none');
                    if (chequeIdGroup) chequeIdGroup.classList.remove('d-none');
                } else {
                    if (transferIdGroup) transferIdGroup.classList.add('d-none');
                    if (chequeIdGroup) chequeIdGroup.classList.add('d-none');
                }
            }

            function toggleCalculationFields() {
                if (calculationTypeSelect && calculationTypeSelect.value === 'rate_per_sq_ft') {
                    if (parkingRatePerSqFtGroup) parkingRatePerSqFtGroup.classList.remove('d-none');
                    if (totalSqFtGroup) totalSqFtGroup.classList.remove('d-none');
                } else {
                    if (parkingRatePerSqFtGroup) parkingRatePerSqFtGroup.classList.add('d-none');
                    if (totalSqFtGroup) totalSqFtGroup.classList.add('d-none');
                }
            }

            function toggleAreaCalculationFields() {
                const buildUpAreaField = modalElement.querySelector('#build_up_area');
                const carpetAreaField = modalElement.querySelector('#carpet_area');

                if (areaCalculationTypeSelect && areaCalculationTypeSelect.value === 'build_up_area') {
                    if (buildUpAreaField) buildUpAreaField.classList.remove('d-none');
                    if (carpetAreaField) carpetAreaField.classList.add('d-none');
                } else {
                    if (buildUpAreaField) buildUpAreaField.classList.add('d-none');
                    if (carpetAreaField) carpetAreaField.classList.remove('d-none');
                }
            }

            function updateTotalAmount() {
                const saleAmount = saleInput ? parseFloat(saleInput.value) || 0 : 0;
                const areaCalculationType = areaCalculationTypeSelect ? areaCalculationTypeSelect.value : '';

                if (!roomId) {
                    console.error('Room ID is not defined.');
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.sales.caltype') }}",
                    data: {
                        room_id: roomId,
                        type: areaCalculationType,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: "json",
                    success: function(resultData) {
                        console.log('Result Data:', resultData);
                        let totalRate = parseInt(resultData.sqft) * parseFloat(saleAmount);
                        console.log('Total Rate:', totalRate);

                        if (calculationTypeSelect.value === 'rate_per_sq_ft') {
                            const parkingRate = parseFloat(parkingRateInput.value) || 0;
                            const totalSqFt = parseFloat(totalSqFtInput.value) || 0;
                            const parkingAmount = parkingRate * totalSqFt;
                            totalRate += parkingAmount;
                        }

                        const discountPercent = parseFloat(discountInput.value) || 0;
                        const discountAmount = totalRate * (discountPercent / 100);
                        totalRate -= discountAmount;

                        const gstPercent = parseFloat(gstInput.value) || 0;
                        const gstAmount = totalRate * (gstPercent / 100);
                        totalRate += gstAmount;

                        if (totalElement) totalElement.textContent = totalRate.toFixed(2);

                        const advanceAmount = parseFloat(advanceAmountInput.value) || 0;
                        const remainingBalance = totalRate - advanceAmount;
                        if (remainingBalanceElement) remainingBalanceElement.textContent = remainingBalance.toFixed(2);

                        calculateInHandAmount(roomId, totalRate);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }

            function calculateInHandAmount(roomId, totalRate) {
                const cashInHandPercent = parseFloat(document.getElementById(`cash_in_hand_percent${roomId}`).value) || 0;
                const cashInHandAmount = (cashInHandPercent / 100) * totalRate;
                document.getElementById(`in_hand_amount${roomId}`).value = cashInHandAmount.toFixed(2);
            }

            if (advancePaymentSelect) {
                advancePaymentSelect.addEventListener('change', toggleAdvancePaymentFields);
            }
            if (paymentMethodSelect) {
                paymentMethodSelect.addEventListener('change', togglePaymentMethodFields);
            }
            if (calculationTypeSelect) {
                calculationTypeSelect.addEventListener('change', toggleCalculationFields);
            }
            if (areaCalculationTypeSelect) {
                areaCalculationTypeSelect.addEventListener('change', updateTotalAmount);
            }
            if (saleInput) {
                saleInput.addEventListener('input', updateTotalAmount);
            }
            if (parkingRateInput) {
                parkingRateInput.addEventListener('input', updateTotalAmount);
            }
            if (totalSqFtInput) {
                totalSqFtInput.addEventListener('input', updateTotalAmount);
            }
            if (gstInput) {
                gstInput.addEventListener('input', updateTotalAmount);
            }
            if (discountInput) {
                discountInput.addEventListener('input', updateTotalAmount);
            }
            if (advanceAmountInput) {
                advanceAmountInput.addEventListener('input', updateTotalAmount);
            }
            if (cashInHandPercentInput) {
                cashInHandPercentInput.addEventListener('input', () => calculateInHandAmount(roomId, parseFloat(totalElement.textContent)));
            }

            toggleAdvancePaymentFields();
            togglePaymentMethodFields();
            toggleCalculationFields();
            toggleAreaCalculationFields();
            updateTotalAmount();

            const roomType = modalElement.getAttribute('data-room-type');
            showRelevantAreaFields(roomType);
        });
    });
</script>
@endsection
