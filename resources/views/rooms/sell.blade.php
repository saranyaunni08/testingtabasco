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
                    <input type="number" class="form-control" id="sale_amount" name="sale_amount" oninput="calculateTotalRate({{ $room->id }})" required>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="font-weight-bold" for="area_calculation_type">Area Calculation Type</label>
                    <select class="form-control" id="area_calculation_type" name="area_calculation_type" onchange="calculateTotalRate({{ $room->id }})" required>
                        <option value="" selected disabled>Select</option>
                        <option value="carpet_area_rate">Carpet Area</option>
                        <option value="built_up_area_rate">Super Built-up Area</option>
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
        const areaCalculationTypeSelect = modalElement.querySelector('#area_calculation_type');
        const calculationTypeSelect = modalElement.querySelector('#calculation_type');
        const parkingRatePerSqFtInput = modalElement.querySelector('#parking_rate_per_sq_ft');
        const totalSqFtForParkingInput = modalElement.querySelector('#total_sq_ft_for_parking');
        const discountPercentInput = modalElement.querySelector('#discount_percent');
        const gstPercentInput = modalElement.querySelector('#gst_percent');
        const saleAmountInput = modalElement.querySelector('#sale_amount');
        const flatBuildUpAreaInput = modalElement.querySelector('#flat_build_up_area');
        const flatCarpetAreaInput = modalElement.querySelector('#flat_carpet_area');
        const inHandPercentInput = modalElement.querySelector(`#cash_in_hand_percent${roomId}`);
        const inHandAmountInput = modalElement.querySelector(`#in_hand_amount${roomId}`);
        const gstAmountDisplay = modalElement.querySelector('#gst_amount');
        const totalAmountDisplay = modalElement.querySelector('#total');
        const remainingBalanceDisplay = modalElement.querySelector('#remaining_balance');

        function calculateTotalAmount() {
            let saleAmount = parseFloat(saleAmountInput.value) || 0;
            let discountPercent = parseFloat(discountPercentInput.value) || 0;
            let gstPercent = parseFloat(gstPercentInput.value) || 0;
            let parkingAmount = 0;

            if (areaCalculationTypeSelect.value === 'built_up_area_rate') {
                saleAmount *= parseFloat(flatBuildUpAreaInput.value) || 0;
            } else if (areaCalculationTypeSelect.value === 'carpet_area_rate') {
                saleAmount *= parseFloat(flatCarpetAreaInput.value) || 0;
            }

            if (calculationTypeSelect.value === 'rate_per_sq_ft') {
                parkingAmount = (parseFloat(parkingRatePerSqFtInput.value) || 0) * (parseFloat(totalSqFtForParkingInput.value) || 0);
            }

            let totalAmountBeforeDiscount = saleAmount + parkingAmount;
            let discountAmount = (totalAmountBeforeDiscount * discountPercent) / 100;
            let totalAmountAfterDiscount = totalAmountBeforeDiscount - discountAmount;
            let gstAmount = (totalAmountAfterDiscount * gstPercent) / 100;
            let totalAmount = totalAmountAfterDiscount + gstAmount;

            gstAmountDisplay.textContent = gstAmount.toFixed(2);
            totalAmountDisplay.textContent = totalAmount.toFixed(2);

            const inHandPercent = parseFloat(inHandPercentInput.value) || 0;
            const inHandAmount = (totalAmount * inHandPercent) / 100;
            inHandAmountInput.value = inHandAmount.toFixed(2);

            const advanceAmount = parseFloat(modalElement.querySelector('#advance_amount').value) || 0;
            const remainingBalance = totalAmount - advanceAmount;
            remainingBalanceDisplay.textContent = remainingBalance.toFixed(2);
        }

        function toggleAdvancePaymentFields() {
            const advancePaymentSelect = modalElement.querySelector('#advance_payment');
            const advanceAmountGroup = modalElement.querySelector('#advance_amount_group');
            const paymentMethodGroup = modalElement.querySelector('#payment_method_group');
            const transferIdGroup = modalElement.querySelector('#transfer_id_group');
            const chequeIdGroup = modalElement.querySelector('#cheque_id_group');

            if (advancePaymentSelect.value === 'now') {
                advanceAmountGroup.classList.remove('d-none');
                paymentMethodGroup.classList.remove('d-none');
            } else {
                advanceAmountGroup.classList.add('d-none');
                paymentMethodGroup.classList.add('d-none');
                transferIdGroup.classList.add('d-none');
                chequeIdGroup.classList.add('d-none');
            }
        }

        function togglePaymentMethodFields() {
            const paymentMethodSelect = modalElement.querySelector('#payment_method');
            const transferIdGroup = modalElement.querySelector('#transfer_id_group');
            const chequeIdGroup = modalElement.querySelector('#cheque_id_group');

            if (paymentMethodSelect.value === 'bank_transfer') {
                transferIdGroup.classList.remove('d-none');
                chequeIdGroup.classList.add('d-none');
            } else if (paymentMethodSelect.value === 'cheque') {
                transferIdGroup.classList.add('d-none');
                chequeIdGroup.classList.remove('d-none');
            } else {
                transferIdGroup.classList.add('d-none');
                chequeIdGroup.classList.add('d-none');
            }
        }

        function toggleCalculationFields() {
            const parkingRatePerSqFtGroup = modalElement.querySelector(`#parking_rate_per_sq_ft_group${roomId}`);
            const totalSqFtGroup = modalElement.querySelector(`#total_sq_ft_group${roomId}`);

            if (calculationTypeSelect.value === 'rate_per_sq_ft') {
                parkingRatePerSqFtGroup.classList.remove('d-none');
                totalSqFtGroup.classList.remove('d-none');
            } else {
                parkingRatePerSqFtGroup.classList.add('d-none');
                totalSqFtGroup.classList.add('d-none');
            }
        }

        // Attach event listeners for calculating the total amount
        saleAmountInput.addEventListener('input', calculateTotalAmount);
        discountPercentInput.addEventListener('input', calculateTotalAmount);
        gstPercentInput.addEventListener('input', calculateTotalAmount);
        inHandPercentInput.addEventListener('input', calculateTotalAmount);
        areaCalculationTypeSelect.addEventListener('change', calculateTotalAmount);
        parkingRatePerSqFtInput.addEventListener('input', calculateTotalAmount);
        totalSqFtForParkingInput.addEventListener('input', calculateTotalAmount);
        calculationTypeSelect.addEventListener('change', calculateTotalAmount);

        // Attach change event listeners for toggling fields
        modalElement.querySelector('#advance_payment').addEventListener('change', toggleAdvancePaymentFields);
        modalElement.querySelector('#payment_method').addEventListener('change', togglePaymentMethodFields);
        calculationTypeSelect.addEventListener('change', toggleCalculationFields);

        // Initialize the fields based on the current values
        calculateTotalAmount();
        toggleAdvancePaymentFields();
        togglePaymentMethodFields();
        toggleCalculationFields();
    });
});

</script>    
@endsection