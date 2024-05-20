@extends('layouts.default', ['title' => 'Add New Building', 'page' => 'buildings'])

@section('content')
  <div class="container-fluid py-4">
    <div class="row">
      <div class="col-12">
        <div class="card my-4" id="personal-details-section">
          <!-- Personal Details Section -->
          <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="bg-gradient-info shadow-info border-radius-lg pt-4 pb-3">
              <h6 class="text-white text-capitalize ps-3">Personal Details</h6>
            </div>
          </div>
          <div class="card-body px-0 pb-2">
            <div class="row p-4">
              <form action="" method="POST">
                @csrf
                <div class="row justify-content-center">
                  <div class="col-lg-8">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="customer_name" class="form-label">Name</label>
                          <input type="text" id="customer_name" name="customer_name" class="form-control border-0">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="customer_address" class="form-label">Address</label>
                          <textarea id="customer_address" name="customer_address" class="form-control border-0"></textarea>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="customer_street" class="form-label">Street</label>
                          <input type="text" id="customer_street" name="customer_street" class="form-control border-0">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="customer_city" class="form-label">City</label>
                          <input type="text" id="customer_city" name="customer_city" class="form-control border-0">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="customer_phone" class="form-label">Phone Number</label>
                          <input type="text" id="customer_phone" name="customer_phone" class="form-control border-0">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="customer_pin" class="form-label">Pin Code</label>
                          <input type="text" id="customer_pin" name="customer_pin" class="form-control border-0">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="customer_state" class="form-label">State</label>
                          <input type="text" id="customer_state" name="customer_state" class="form-control border-0">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="customer_country" class="form-label">Country</label>
                          <input type="text" id="customer_country" name="customer_country" class="form-control border-0">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-12">
        <div class="card my-4" id="payment-details-section" style="display: none;">
          <!-- Payment Details Section -->
          <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="bg-gradient-info shadow-info border-radius-lg pt-4 pb-3">
              <h6 class="text-white text-capitalize ps-3">Payment Details</h6>
            </div>
          </div>
          <div class="card-body px-0 pb-2">
            <div class="row p-4">
              <form action="" method="POST">
                @csrf
                <div class="row justify-content-center">
                  <div class="col-lg-8">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="sale_amount" class="form-label">Sale Amount</label>
                          <input type="text" id="sale_amount" name="sale_amount" value="12000" class="form-control border-0" readonly>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="discount_amount" class="form-label">Discount</label>
                          <input type="text" id="discount_amount" name="discount_amount" value="12%" class="form-control border-0" readonly>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="sale_amount" class="form-label">Advance amount</label>
                          <input type="text" id="sale_amount" name="sale_amount" value="" class="form-control border-0">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="payment_method" class="form-label">Payment</label>
                          <select id="payment_method" name="payment_method" class="form-select border-0">
                            <option value="">Select Payment</option>
                            <option value="Full Payment">Full Payment</option>
                            <option value="Payment via Installment">Payment via Installment</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-6 payment-options">
                        <div class="mb-3">
                          <label for="installment_period" class="form-label">Installment Period</label>
                          <select id="installment_period" name="installment_period" class="form-select border-0">
                            <option value="">Select Installment Period</option>
                            <option value="within 6 month">within 6 month</option>
                            <option value="within 8 month">within 8 month</option>
                            <option value="within 12 month">within 12 month</option>
                          </select>
                        </div>
                      </div>

                      <div class="col-md-6 payment-options">
                        <div class="mb-3">
                          <label for="installment_period" class="form-label">Installment amount</label>
                          <input type="text" id="customer_state" name="customer_state" class="form-control border-0" value="3000">
                        </div>
                      </div>
                      <div class="col-md-6 payment-options">
                        <div class="mb-3">
                          <label for="payment_using" class="form-label">Payment Using</label>
                          <select id="payment_using" name="payment_using" class="form-select border-0">
                            <option value="">Select Payment Method</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Cheque">Cheque</option>
                            <option value="UPI Payment">UPI Payment</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-12">
        <div class="card my-4" id="bank-details-section" style="display: none;">
          <!-- Bank Details Section -->
          <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="bg-gradient-info shadow-info border-radius-lg pt-4 pb-3">
              <h6 class="text-white text-capitalize ps-3">Bank Details</h6>
            </div>
          </div>
          <div class="card-body px-0 pb-2">
            <div class="row p-4">
              <form action="" method="POST">
                @csrf
                <div class="row justify-content-center">
                  <div class="col-lg-8">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="sale_amount" class="form-label">Bank name</label>
                          <input type="text" id="bank_name" name="bank_name"  class="form-control border-0">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="discount_amount" class="form-label">Branch</label> 
                          <input type="text" id="branch" name="branch" class="form-control border-0">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label for="payment_method" class="form-label">Account no.</label>
                          <input type="text" id="account_num" name="account_num" class="form-control border-0">
                        </div>
                      </div>
                      <div class="col-md-6 payment-options">
                        <div class="mb-3">
                          <label for="installment_period" class="form-label">IFSC code</label>
                          <input type="text" id="ifsc" name="ifsc"  class="form-control border-0">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Next Buttons -->
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <button id="next-personal" class="btn btn-primary">Next (Payment Details)</button>
      <button id="next-payment" class="btn btn-primary" style="display: none;">Next (Bank Details)</button>
      <button id="next-bank" class="btn btn-primary" style="display: none;">Next (Bank Details)</button>
      <button id="submit-form" class="btn btn-primary" style="display: none;">Submit</button>
    </div>
  </div>

  <!-- Back Buttons -->
  <div class="row justify-content-center mt-4">
    <div class="col-lg-8">
      <button id="back-personal" class="btn btn-secondary" style="display: none;">Back to Personal Details</button>
      <button id="back-payment" class="btn btn-secondary" style="display: none;">Back to Payment Details</button>
    </div>
  </div>

  <!-- JavaScript for Step-by-Step Form -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const personalDetailsSection = document.getElementById('personal-details-section');
      const paymentDetailsSection = document.getElementById('payment-details-section');
      const bankDetailsSection = document.getElementById('bank-details-section');

      const backPersonalBtn = document.getElementById('back-personal');
      const backPaymentBtn = document.getElementById('back-payment');
      const nextPersonalBtn = document.getElementById('next-personal');
      const nextPaymentBtn = document.getElementById('next-payment');
      const nextBankBtn = document.getElementById('next-bank');
      const submitFormBtn = document.getElementById('submit-form');

      backPersonalBtn.addEventListener('click', function () {
        personalDetailsSection.style.display = 'block';
        paymentDetailsSection.style.display = 'none';
        bankDetails Section.style.display = 'none';
        backPersonalBtn.style.display = 'none';
        nextPersonalBtn.style.display = 'block';
        nextPaymentBtn.style.display = 'none';
        nextBankBtn.style.display = 'none';
        submitFormBtn.style.display = 'none';
      });

      backPaymentBtn.addEventListener('click', function () {
        personalDetailsSection.style.display = 'none';
        paymentDetailsSection.style.display = 'block';
        bankDetailsSection.style.display = 'none';
        backPersonalBtn.style.display = 'block';
        nextPersonalBtn.style.display = 'none';
        nextPaymentBtn.style.display = 'block';
        nextBankBtn.style.display = 'none';
        submitFormBtn.style.display = 'none';
      });

      nextPersonalBtn.addEventListener('click', function () {
        personalDetailsSection.style.display = 'none';
        paymentDetailsSection.style.display = 'block';
        bankDetailsSection.style.display = 'none';
        backPersonalBtn.style.display = 'block';
        nextPersonalBtn.style.display = 'none';
        nextPaymentBtn.style.display = 'block';
        nextBankBtn.style.display = 'none';
        submitFormBtn.style.display = 'none';
      });

      nextPaymentBtn.addEventListener('click', function () {
        personalDetailsSection.style.display = 'none';
        paymentDetailsSection.style.display = 'none';
        bankDetailsSection.style.display = 'block';
        backPersonalBtn.style.display = 'none';
        nextPersonalBtn.style.display = 'none';
        nextPaymentBtn.style.display = 'none';
        nextBankBtn.style.display = 'none';
        submitFormBtn.style.display = 'block';
      });
    });
  </script>
@endsection