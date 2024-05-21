@extends('layouts.default', ['title' => 'Add New Building', 'page' => 'buildings'])

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">

                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-info border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Add Sale</h6>
                    </div>
                </div>

                <div class="card-body px-0 pb-2">
                    <div class="row p-4">
                        <form action="{{ route('admin.sales.store') }}" method="POST">
                            @csrf


                            {{-- <input type="hidden" id="room_id" name="room_id" value="{{ $room->id }}" /> --}}

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="customer_name" class="form-label">Name</label>
                                    <input type="text" id="customer_name" name="customer_name" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="customer_address" class="form-label">Address</label>
                                    <textarea id="customer_address" name="customer_address" class="form-control" required></textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="customer_street" class="form-label">Street</label>
                                    <input type="text" id="customer_street" name="customer_street" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="customer_city" class="form-label">City</label>
                                    <input type="text" id="customer_city" name="customer_city" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="customer_phone" class="form-label">Phone Number</label>
                                    <input type="text" id="customer_phone" name="customer_phone" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="customer_pin" class="form-label">Pin Code</label>
                                    <input type="text" id="customer_pin" name="customer_pin" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="customer_state" class="form-label">State</label>
                                    <input type="text" id="customer_state" name="customer_state" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="customer_country" class="form-label">Country</label>
                                    <input type="text" id="customer_country" name="customer_country" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="sale_amount" class="form-label">Sale Amount</label>
                                    <input type="text" id="sale_amount" name="sale_amount" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="discount_amount" class="form-label">Discount</label>
                                    <input type="text" id="discount_amount" name="discount_amount" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="advance_amount" class="form-label">Advance</label>
                                    <input type="text" id="advance_amount" name="advance_amount" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="payment_method" class="form-label">Payment</label>
                                    <select id="payment_method" name="payment_method" class="form-select" required onchange="toggleInstallmentFields()">
                                        <option value="">Select Payment</option>
                                        <option value="Full Payment">Full Payment</option>
                                        <option value="Payment via Installment">Payment via Installment</option>
                                    </select>
                                </div>

                                <div class="row" id="installmentFields" style="display: none;">
                                    <div class="col-md-6 mb-3 payment-options">
                                        <label for="installment_period" class="form-label">Installment Period</label>
                                        <select id="installment_period" name="installment_period" class="form-select">
                                            <option value="">Select Installment Period</option>
                                            <option value="within 6 months">within 6 months</option>
                                            <option value="within 8 months">within 8 months</option>
                                            <option value="within 12 months">within 12 months</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3 payment-options">
                                        <label for="installment_amount" class="form-label">Installment Amount</label>
                                        <input type="text" id="installment_amount" name="installment_amount" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3 payment-options">
                                    <label for="payment_using" class="form-label">Payment Using</label>
                                    <select id="payment_using" name="payment_using" class="form-select">
                                        <option value="">Select Payment Method</option>
                                        <option value="Bank Transfer">Bank Transfer</option>
                                        <option value="Cheque">Cheque</option>
                                        <option value="UPI Payment">UPI Payment</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="bank_name" class="form-label">Bank Name</label>
                                    <input type="text" id="bank_name" name="bank_name" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="branch" class="form-label">Branch</label>
                                    <input type="text" id="branch" name="branch" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="account_num" class="form-label">Account No.</label>
                                    <input type="text" id="account_num" name="account_num" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="ifsc" class="form-label">IFSC Code</label>
                                    <input type="text" id="ifsc" name="ifsc" class="form-control">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleInstallmentFields() {
        var paymentMethod = document.getElementById('payment_method').value;
        var installmentFields = document.getElementById('installmentFields');
        var installmentPeriod = document.getElementById('installment_period');
        var installmentAmount = document.getElementById('installment_amount');
        
        if (paymentMethod === 'Full Payment') {
            installmentFields.style.display = 'none';
            installmentPeriod.disabled = true;
            installmentAmount.disabled = true;
        } else {
            installmentFields.style.display = 'block';
            installmentPeriod.disabled = false;
            installmentAmount.disabled = false;
        }
    }
</script>
@endsection
