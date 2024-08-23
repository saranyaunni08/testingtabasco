@extends('layouts.default')

@section('content')
<div class="container" id="sellModal{{ $room->id }}">
    <h1>Sell Room {{ $room->name }}</h1>
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
            @if($room->room_type == 'Flat')
            <div class="col-6">
                <div class="form-group">
                    <label class="font-weight-bold" for="flat_build_up_area">Super Build-Up Area</label>
                    <input type="text" class="form-control" id="flat_build_up_area" name="flat_build_up_area" value="{{ $room->flat_build_up_area }}" readonly>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="font-weight-bold" for="flat_carpet_area">Carpet Area</label>
                    <input type="text" class="form-control" id="flat_carpet_area" name="flat_carpet_area" value="{{ $room->flat_carpet_area }}" readonly>
                </div>
            </div>
            @endif  
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
                    <label class="font-weight-bold" for="gst_percent">GST Percent</label>
                    <input type="number" step="0.01" class="form-control" id="gst_percent" name="gst_percent" required>
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
                <div class="form-group">
                    <label class="font-weight-bold" for="partner_name">Partner Name</label>
                    <input type="text" class="form-control" id="partner_name" name="partner_name" required>
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
                <div class="form-group">
                    <label class="font-weight-bold" for="installment_date">Installment Date</label>
                    <input type="date" class="form-control" id="installment_date" name="installment_date">
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

            <div  id="gst_amount_group" class="col-12">
                <h4 for="gst_amount">GST Amount :₹<span id="gst_amount"></span> </h4>
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
                } else {
                    if (advanceAmountGroup) advanceAmountGroup.classList.add('d-none');
                    if (paymentMethodGroup) paymentMethodGroup.classList.add('d-none');
                    if (transferIdGroup) transferIdGroup.classList.add('d-none');
                    if (chequeIdGroup) chequeIdGroup.classList.add('d-none');
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

             
            }

            function updateTotalAmount() {
                const saleAmount = saleInput ? saleInput.value : 0; 
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
                        type: areaCalculationType, // Ensure this matches the expected values on the backend
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: "json",
                    success: function(resultData) {
                        console.log('Result Data:', resultData); 

                        // Make sure that the resultData contains correct values for 'sqft'
                        let totalRate = parseInt(resultData.sqft) * parseInt(saleAmount);
                        console.log('Total Rate:', totalRate);

                        // Add parking amount if applicable
                        if (calculationTypeSelect.value === 'rate_per_sq_ft') {
                            const parkingRate = parseFloat(parkingRateInput.value) || 0;
                            const totalSqFt = parseFloat(totalSqFtInput.value) || 0;
                            const parkingAmount = parkingRate * totalSqFt;
                            totalRate += parkingAmount;
                        }

                        // Apply discount if applicable
                        const discountPercent = parseFloat(discountInput.value) || 0;
                        const discountAmount = totalRate * (discountPercent / 100);
                        totalRate -= discountAmount;

                        // Add GST if applicable
                        const gstPercent = parseFloat(gstInput.value) || 0;
                        const gstAmount = totalRate * (gstPercent / 100);
                        totalRate += gstAmount;

                        if (totalElement) totalElement.textContent = totalRate.toFixed(2);

                        // Calculate remaining balance
                        const advanceAmount = parseFloat(advanceAmountInput.value) || 0;
                        const remainingBalance = totalRate - advanceAmount;
                        if (remainingBalanceElement) remainingBalanceElement.textContent = remainingBalance.toFixed(2);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
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


    <script>
        document.addEventListener('DOMContentLoaded', function() {
          document.querySelectorAll('button[data-toggle="modal"]').forEach(button => {
            button.addEventListener('click', function() {
              const roomId = this.getAttribute('data-room-id');
              const buildingId = this.getAttribute('data-building-id');
              const redirectUrl = `{{ route('admin.rooms.destroy.flat', ['roomId' => '__ROOM_ID__', 'buildingId' => '__BUILDING_ID__']) }}`.replace('__ROOM_ID__', roomId).replace('__BUILDING_ID__', buildingId);
              document.getElementById('redirectUrl' + roomId).value = redirectUrl;
            });
          });
        });
      </script>
    
    
    
@endsection