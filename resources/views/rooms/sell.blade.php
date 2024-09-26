@extends('layouts.default')

@section('content')
<div class="container">
    <h2>Sell Room</h2>
    <form action="{{ route('admin.sales.store') }}" method="POST">
        @csrf
        <!-- Customer Name -->
        <div class="form-group">
            <label class="font-weight-bold" for="customer_name">Customer Name</label>
            <input type="text" class="form-control" id="customer_name" name="customer_name" required>
        </div>

        <!-- Customer Email -->
        <div class="form-group">
            <label class="font-weight-bold" for="customer_email">Customer Email</label>
            <input type="email" class="form-control" id="customer_email" name="customer_email" required>
        </div>

        <!-- Customer Contact -->
        <div class="form-group">
            <label class="font-weight-bold" for="customer_contact">Customer Contact</label>
            <input type="text" class="form-control" id="customer_contact" name="customer_contact" required>
        </div>

           <!-- Sale Amount -->
           <div class="form-group">
            <label class="font-weight-bold" for="sale_amount">Sale Amount (in sq ft)</label>
            <input type="number" class="form-control" id="sale_amount" name="sale_amount" required>
        </div>

        <!-- Area Calculation Type -->
        <div class="form-group">
            <label class="font-weight-bold" for="area_calculation_type">Area Calculation Type</label>
            <select class="form-control" id="area_calculation_type" name="area_calculation_type" required>
                <option value="" disabled selected>Select Area Type</option>
                <option value="super_build_up_area">Super Build-Up Area</option>
                <option value="carpet_area">Carpet Area</option>
            </select>
        </div>

        <!-- Read-only Fields for Super Build-Up and Carpet Area -->
        @if($room->room_type == 'Flat')
            <div class="form-group">
                <label class="font-weight-bold" for="flat_build_up_area">Super Build-Up Area (sq ft)</label>
                <input type="text" class="form-control" id="flat_build_up_area" name="flat_build_up_area" value="{{ $room->flat_build_up_area }}" readonly>
            </div>
            <div class="form-group">
                <label class="font-weight-bold" for="flat_carpet_area">Carpet Area (sq ft)</label>
                <input type="text" class="form-control" id="flat_carpet_area" name="flat_carpet_area" value="{{ $room->flat_carpet_area }}" readonly>
            </div>
        @endif

        <!-- Total Amount (Read-only) -->
        <div class="form-group">
            <label class="font-weight-bold" for="total_amount">Total Amount</label>
            <input type="text" class="form-control" id="total_amount" name="total_amount" readonly>
        </div>

        <!-- Discount Percentage -->
        <div class="form-group">
            <label class="font-weight-bold" for="discount_percentage">Discount Percentage (%)</label>
            <input type="number" class="form-control" id="discount_percentage" name="discount_percentage" min="0" max="100" step="0.01">
        </div>

        <!-- Discount Amount -->
        <div class="form-group">
            <label class="font-weight-bold" for="discount_amount">Discount Amount</label>
            <input type="text" class="form-control" id="discount_amount" name="discount_amount">
        </div>

        <!-- Final Amount (After Discount) -->
        <div class="form-group">
            <label class="font-weight-bold" for="final_amount">Final Amount (After Discount)</label>
            <input type="text" class="form-control" id="final_amount" name="final_amount" readonly>
        </div>

        <!-- Cash Value Percentage -->
        <div class="form-group">
            <label class="font-weight-bold" for="cash_value_percentage">Cash Value Percentage (%)</label>
            <input type="number" class="form-control" id="cash_value_percentage" name="cash_value_percentage" min="0" max="100" step="0.01">
        </div>

        <!-- Cash Value Amount -->
        <div class="form-group">
            <label class="font-weight-bold" for="cash_value_amount">Cash Value Amount</label>
            <input type="text" class="form-control" id="cash_value_amount" name="cash_value_amount">
        </div>
        <!-- Additional Amount Section -->
        <div class="form-group">
            <label>Additional Amounts</label>
            <div id="additional-amounts-container">
                <!-- Additional fields will be appended here dynamically -->
            </div>
            <button type="button" id="add-more" class="btn btn-success mt-2">+ Add More</button>
        </div>

        <!-- Total Cash Value (With Additional Amounts) -->
        <div class="form-group">
            <label for="total_cash_value">Total Cash Value (with Additional Amounts)</label>
            <input type="text" class="form-control" id="total_cash_value" name="total_cash_value" readonly>
        </div>
         
        <div class="form-group">
            <label for="total_received_amount">Total Received Amount</label>
            <input type="number" class="form-control" id="total_received_amount" name="total_received_amount" oninput="updatePartnerFields()">
        </div>
        
        <!-- Partner Selection (Checkboxes) -->
        <div class="form-group">
            <label>Select Partners</label>
            <div id="partner_checkbox_container">
                @foreach($partners as $partner)
                <div class="form-check">
                    <input class="form-check-input partner-checkbox" type="checkbox" value="{{ $partner->id }}" id="partner_{{ $partner->id }}" onchange="togglePartnerFields({{ $partner->id }})">
                    <label class="form-check-label" for="partner_{{ $partner->id }}">
                        {{ $partner->first_name }}
                    </label>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Partner Amount Distribution -->
        <div id="partner_distribution_container"></div>

        <div id="total_percentage_error" style="color:red;"></div>


        <div id="additional-expenses-container">
            <h5>Other Expenses</h5>
            <div class="row mb-2" id="additional-expense-0">
                <div class="col-md-6">
                    <input type="text" class="form-control" name="expense_descriptions[]" placeholder="Description">
                </div>
                <div class="col-md-4">
                    <input type="number" class="form-control percentage-input" name="expense_percentages[]" placeholder="Percentage" step="0.01" oninput="calculateExpenseAmount(this); updateTotalPercentage()">
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control amount-display" placeholder="Amount" oninput="calculatePercentage(this);" />
                </div>
            </div>
            <button type="button" class="btn btn-success mt-2" id="add-more-expenses">Add Expenses</button>
        </div>
        
        

        <!-- Remaining Cash Value -->
        <div class="form-group">
            <label for="remaining_cash_value">Remaining Cash Value</label>
            <input type="text" class="form-control" id="remaining_cash_value" name="remaining_cash_value" readonly>
        </div>


        <div class="form-group">
            <label for="total_cheque_value">Total Cheque Value</label>
            <input type="text" id="total_cheque_value" class="form-control" readonly>
        </div>
        

        <div id="additional-expenses-container">
            <h5>Other Expenses</h5>
            <div class="row mb-2" id="expense-container">
                <div class="col-md-6">
                    <input type="text" placeholder="Expense Description" class="form-control expense-description" />
                </div>
                <div class="col-md-6">
                    <input type="number" placeholder="Expense Amount" class="form-control expense-amount" />
                </div>
            </div><br>
            <button id="add-expense" class="btn btn-success mt-2">Add Expense</button>
        </div>
        

        <div class="form-group">
            <label>Total Cheque Value (with Additional Amounts):</label>
            <input type="text" id="total_cheque_value_with_additional" class="form-control" readonly />
        </div>

        <div class="form-group ">
            <label >Gst Percentage</label>
            <input type="number" id="gst_percentage" placeholder="GST Percentage" oninput="calculateTotalChequeValueWithAdditional()" class="form-control" />
        </div>
        <div class="form-group ">
            <label >Gst Amount</label>
            <input type="text" id="gst_amount" placeholder="GST Amount" class="form-control" readonly  />
        </div>
        
        <div class="form-group ">
            <label >Total Cheque Value (with Gst):</label>
            <input type="text" id="total_cheque_value_with_gst" placeholder="Total Cheque Value + GST" class="form-control" readonly />
        <br>
            <h5>Received Amount:</h5>
            <input type="number" id="received_cheque_value" class="form-control" placeholder="Received Amount" oninput="calculateBalance()" />
        <br>
            <h5>Balance Amount:</h5>
            <input type="text" id="balance_amount" class="form-control" placeholder="Balance Amount" readonly />
        </div>

        <div id="loan-type-container" style="display: none;">
            <label for="loan_type">Select Loan Type:</label>
            <select id="loan_type" class="form-control" onchange="handleLoanTypeChange()">
                <option value="">Select...</option>
                <option value="bank">Bank</option>
                <option value="directors">Director's</option>
                <option value="others">Others</option>
            </select>
        </div>
        
        <div id="other-loan-description-container" style="display: none;">
            <label for="other_loan_description">Please specify:</label>
            <input type="text" id="other_loan_description" class="form-control" placeholder="Describe Other Loan Type">
        </div>


        <div id="installment-container" style="display: none;">
            <label for="installment_frequency">Installment Frequency:</label>
            <select id="installment_frequency" class="form-control">
                <option value="">Select Frequency...</option>
                <option value="monthly">Every Month</option>
                <option value="3months">Every 3 Months</option>
                <option value="6months">Every 6 Months</option>
            </select>
        
            <label for="installment_date">Installment Start Date:</label>
            <input type="date" id="installment_date" class="form-control">
        
            <label for="no_of_installments">Number of Installments:</label>
            <input type="number" id="no_of_installments" class="form-control" placeholder="Enter No. of Installments" oninput="calculateInstallmentAmount()">
        
            <label for="installment_amount">Installment Amount (auto-calculated):</label>
            <input type="number" id="installment_amount" class="form-control" readonly>
        </div>
        <div id="grand-total-container">
            <label for="grand_total_amount">Grand Total Amount (auto-calculated):</label>
            <input type="number" id="grand_total_amount" class="form-control" readonly>
        </div>
        

        <br><br>
        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Submit Sale</button>
    </div><br>
    </form>
</div>
   

<script>
    document.getElementById('sale_amount').addEventListener('input', calculateTotalAmount);
    document.getElementById('area_calculation_type').addEventListener('change', calculateTotalAmount);
    document.getElementById('discount_percentage').addEventListener('input', calculateDiscountFromPercentage);
    document.getElementById('discount_amount').addEventListener('input', calculateDiscountFromAmount);
    document.getElementById('cash_value_percentage').addEventListener('input', calculateCashFromPercentage);
    document.getElementById('cash_value_amount').addEventListener('input', calculateCashFromAmount);

    function calculateTotalAmount() {
        let saleAmount = parseFloat(document.getElementById('sale_amount').value);
        let areaType = document.getElementById('area_calculation_type').value;
        let totalAmount = 0;

        if (saleAmount && areaType) {
            if (areaType === 'super_build_up_area') {
                totalAmount = saleAmount * parseFloat(document.getElementById('flat_build_up_area')?.value || 0);
            } else if (areaType === 'carpet_area') {
                totalAmount = saleAmount * parseFloat(document.getElementById('flat_carpet_area')?.value || 0);
            }
        }

        document.getElementById('total_amount').value = totalAmount.toFixed(2);
        calculateDiscountFromPercentage();
    }

    function calculateDiscountFromPercentage() {
        let totalAmount = parseFloat(document.getElementById('total_amount').value);
        let discountPercentage = parseFloat(document.getElementById('discount_percentage').value);
        let discountAmount = 0;

        if (totalAmount && discountPercentage) {
            discountAmount = totalAmount * (discountPercentage / 100);
            document.getElementById('discount_amount').value = discountAmount.toFixed(2);
        }

        calculateFinalAmount();
    }

    function calculateDiscountFromAmount() {
        let totalAmount = parseFloat(document.getElementById('total_amount').value);
        let discountAmount = parseFloat(document.getElementById('discount_amount').value);
        let discountPercentage = 0;

        if (totalAmount && discountAmount) {
            discountPercentage = (discountAmount / totalAmount) * 100;
            document.getElementById('discount_percentage').value = discountPercentage.toFixed(2);
        }

        calculateFinalAmount();
    }

    function calculateFinalAmount() {
        let totalAmount = parseFloat(document.getElementById('total_amount').value);
        let discountAmount = parseFloat(document.getElementById('discount_amount').value);
        let finalAmount = totalAmount - (discountAmount || 0);

        document.getElementById('final_amount').value = finalAmount.toFixed(2);
        calculateCashFromPercentage();
    }
    function calculateCashFromPercentage() {
        let finalAmount = parseFloat(document.getElementById('final_amount').value);
        let cashPercentage = parseFloat(document.getElementById('cash_value_percentage').value);
        let cashAmount = 0;

        if (finalAmount && cashPercentage) {
            cashAmount = finalAmount * (cashPercentage / 100);
            document.getElementById('cash_value_amount').value = cashAmount.toFixed(2);
        }
    }

    function calculateCashFromAmount() {
        let finalAmount = parseFloat(document.getElementById('final_amount').value);
        let cashAmount = parseFloat(document.getElementById('cash_value_amount').value);
        let cashPercentage = 0;

        if (finalAmount && cashAmount) {
            cashPercentage = (cashAmount / finalAmount) * 100;
            document.getElementById('cash_value_percentage').value = cashPercentage.toFixed(2);
        }
    }
</script>
<script>
    let additionalAmountIndex = 0;

    document.getElementById('cash_value_percentage').addEventListener('input', calculateCashFromPercentage);
    document.getElementById('cash_value_amount').addEventListener('input', calculateCashFromAmount);
    document.getElementById('add-more').addEventListener('click', addAdditionalAmountField);

    function calculateCashFromPercentage() {
        let finalAmount = parseFloat(document.getElementById('final_amount').value);
        let cashPercentage = parseFloat(document.getElementById('cash_value_percentage').value);
        let cashAmount = 0;

        if (finalAmount && cashPercentage) {
            cashAmount = finalAmount * (cashPercentage / 100);
            document.getElementById('cash_value_amount').value = cashAmount.toFixed(2);
        }
        updateTotalCashValue();
    }

    function calculateCashFromAmount() {
        let finalAmount = parseFloat(document.getElementById('final_amount').value);
        let cashAmount = parseFloat(document.getElementById('cash_value_amount').value);
        let cashPercentage = 0;

        if (finalAmount && cashAmount) {
            cashPercentage = (cashAmount / finalAmount) * 100;
            document.getElementById('cash_value_percentage').value = cashPercentage.toFixed(2);
        }
        updateTotalCashValue();
    }

    function addAdditionalAmountField() {
        additionalAmountIndex++;

        const container = document.getElementById('additional-amounts-container');
        const newField = `
            <div class="row mb-2" id="additional-amount-${additionalAmountIndex}">
                <div class="col-md-6">
                    <input type="text" class="form-control" name="additional_descriptions[]" placeholder="Description">
                </div>
                <div class="col-md-4">
                    <input type="number" class="form-control additional-amount" name="additional_amounts[]" placeholder="Amount" step="0.01" oninput="updateTotalCashValue()">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger" onclick="removeAdditionalAmountField(${additionalAmountIndex})">Remove</button>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', newField);
    }

    function removeAdditionalAmountField(index) {
        const element = document.getElementById(`additional-amount-${index}`);
        if (element) element.remove();
        updateTotalCashValue();
    }

    function updateTotalCashValue() {
        let baseCashValue = parseFloat(document.getElementById('cash_value_amount').value) || 0;
        let additionalAmounts = document.querySelectorAll('.additional-amount');
        let totalAdditionalAmount = 0;

        additionalAmounts.forEach(amountField => {
            totalAdditionalAmount += parseFloat(amountField.value) || 0;
        });

        let totalCashValue = baseCashValue + totalAdditionalAmount;
        document.getElementById('total_cash_value').value = totalCashValue.toFixed(2);

    }
</script>
<script>
  // Add event listeners to trigger updates
document.getElementById('cash_value_amount').addEventListener('input', updateTotalCashValue);
document.querySelectorAll('.additional-amount').forEach(field => field.addEventListener('input', updateTotalCashValue));
document.getElementById('received_amount').addEventListener('input', updateRemainingCashValue);

function updateTotalCashValue() {
    let baseCashValue = parseFloat(document.getElementById('cash_value_amount').value) || 0;
    let additionalAmounts = document.querySelectorAll('.additional-amount');
    let totalAdditionalAmount = 0;

    // Sum up all additional amounts
    additionalAmounts.forEach(amountField => {
        totalAdditionalAmount += parseFloat(amountField.value) || 0;
    });

    // Calculate total cash value
    let totalCashValue = baseCashValue + totalAdditionalAmount;
    document.getElementById('total_cash_value').value = totalCashValue.toFixed(2);

    // Debugging log
    console.log("Base Cash Value:", baseCashValue);
    console.log("Total Additional Amounts:", totalAdditionalAmount);
    console.log("Total Cash Value (with additional amounts):", totalCashValue);

    }

function updateRemainingCashValue() {
    let totalCashValue = parseFloat(document.getElementById('total_cash_value').value) || 0;
    let receivedAmount = parseFloat(document.getElementById('received_amount').value) || 0;

    // Calculate remaining cash value
    let remainingCashValue = totalCashValue - receivedAmount;

    // Set remaining cash value in the readonly field
    document.getElementById('remaining_cash_value').value = remainingCashValue.toFixed(2);
}

</script>
<script>
   // Function to show or hide partner fields based on checkbox selection
function togglePartnerFields(partnerId) {
    let container = document.getElementById('partner_distribution_container');
    let checkbox = document.getElementById('partner_' + partnerId);
    
    if (checkbox.checked) {
        // If checked, add percentage and amount fields
        let partnerDiv = document.createElement('div');
        partnerDiv.className = 'partner-field';
        partnerDiv.id = 'partner_field_' + partnerId;
        partnerDiv.innerHTML = `
            <h5>Partner: ${document.querySelector('label[for="partner_' + partnerId + '"]').textContent}</h5>
            <div class="form-group">
                <label for="partner_${partnerId}_percentage">Percentage</label>
                <input type="number" class="form-control partner-percentage" data-partner-id="${partnerId}" id="partner_${partnerId}_percentage" min="0" max="100" oninput="updatePartnerAmount(${partnerId}); validateTotalPercentage();">
            </div>
            <div class="form-group">
                <label for="partner_${partnerId}_amount">Amount</label>
                <input type="number" class="form-control partner-amount" data-partner-id="${partnerId}" id="partner_${partnerId}_amount" oninput="updatePartnerPercentage(${partnerId}); validateTotalPercentage();">
            </div>
        `;
        container.appendChild(partnerDiv);
    } else {
        // If unchecked, remove the fields
        let partnerDiv = document.getElementById('partner_field_' + partnerId);
        if (partnerDiv) {
            container.removeChild(partnerDiv);
        }
        validateTotalPercentage(); // Re-validate total percentage after removing a partner
    }
}

// Function to update partner amount based on percentage entered
function updatePartnerAmount(partnerId) {
    let totalReceivedAmount = parseFloat(document.getElementById('total_received_amount').value) || 0;
    let percentageField = document.getElementById(`partner_${partnerId}_percentage`);
    let amountField = document.getElementById(`partner_${partnerId}_amount`);
    let percentage = parseFloat(percentageField.value) || 0;

    // Calculate amount based on percentage
    let amount = (percentage / 100) * totalReceivedAmount;
    amountField.value = amount.toFixed(2);
}

// Function to update partner percentage based on amount entered
function updatePartnerPercentage(partnerId) {
    let totalReceivedAmount = parseFloat(document.getElementById('total_received_amount').value) || 0;
    let amountField = document.getElementById(`partner_${partnerId}_amount`);
    let percentageField = document.getElementById(`partner_${partnerId}_percentage`);
    let amount = parseFloat(amountField.value) || 0;

    // Calculate percentage based on amount
    let percentage = (amount / totalReceivedAmount) * 100;
    percentageField.value = percentage.toFixed(2);
}

// Function to validate that the total percentage does not exceed 100%
// Function to validate that the total percentage does not exceed 100%
function validateTotalPercentage() {
    let totalPercentage = 0;
    let percentageFields = document.querySelectorAll('.partner-percentage');
    
    percentageFields.forEach(function(field) {
        totalPercentage += parseFloat(field.value) || 0;
    });
    
    // Include "Others" percentage if available
    let othersPercentage = parseFloat(document.getElementById('others_percentage').value) || 0;
    totalPercentage += othersPercentage;

    // Display a warning if the total exceeds 100%
    if (totalPercentage > 100) {
        document.getElementById('percentage_error').textContent = "Total percentage cannot exceed 100%";
        document.getElementById('submit_button').disabled = true;
    } else {
        document.getElementById('percentage_error').textContent = "";
        document.getElementById('submit_button').disabled = false;
    }
}

// Attach event listeners to other percentage fields if needed
document.getElementById('others_percentage').addEventListener('input', validateTotalPercentage);


// Disable the submit button if the validation fails
function disableSubmitButton() {
    document.getElementById('submit_button').disabled = true;
}

// Enable the submit button if the validation passes
function enableSubmitButton() {
    document.getElementById('submit_button').disabled = false;
}

// Function to update others amount based on percentage entered
function updateOthersAmount() {
    let totalReceivedAmount = parseFloat(document.getElementById('total_received_amount').value) || 0;
    let othersPercentage = parseFloat(document.getElementById('others_percentage').value) || 0;

    let othersAmount = (othersPercentage / 100) * totalReceivedAmount;
    document.getElementById('others_amount').value = othersAmount.toFixed(2);

    validateTotalPercentage(); // Revalidate after updating "Others" amount
}

</script>
<script>
let expenseCount = 1; // Initialize counter for additional expenses

// Function to calculate total percentage and update error message
function updateTotalPercentage() {
    const partnerCheckboxes = document.querySelectorAll('.partner-checkbox:checked');
    let totalPartnerPercentage = 0;

    // Sum selected partner percentages
    partnerCheckboxes.forEach(checkbox => {
        const partnerId = checkbox.value;
        const percentageInput = document.querySelector(`input[data-partner-id="${partnerId}"]`);
        if (percentageInput) {
            totalPartnerPercentage += parseFloat(percentageInput.value) || 0;
        }
    });

    // Sum "Other Expenses" percentages
    const expensePercentages = document.querySelectorAll('input[name="expense_percentages[]"]');
    expensePercentages.forEach(input => {
        totalPartnerPercentage += parseFloat(input.value) || 0;
    });

    // Check for total exceeding 100%
    const errorDiv = document.getElementById('total_percentage_error');
    if (totalPartnerPercentage > 100) {
        errorDiv.innerText = 'Total percentage exceeds 100%. Please adjust the values.';
    } else {
        errorDiv.innerText = ''; // Clear the error message
    }
}

// Function to calculate the expense amount based on the percentage input
function calculateExpenseAmount(inputElement) {
    const percentage = parseFloat(inputElement.value) || 0; // Get the percentage
    const totalReceivedAmount = parseFloat(document.getElementById('total_received_amount').value) || 0; // Get total received amount
    const amountDisplay = inputElement.closest('.row').querySelector('.amount-display'); // Get the corresponding amount display input

    // Calculate the expense amount based on the percentage of the total received amount
    const amount = (totalReceivedAmount * (percentage / 100)).toFixed(2);
    amountDisplay.value = amount; // Update the amount display field

    // Update total percentage on change
    updateTotalPercentage();
}

// Function to calculate percentage based on the amount input
function calculatePercentage(inputElement) {
    const amount = parseFloat(inputElement.value) || 0; // Get the amount
    const totalReceivedAmount = parseFloat(document.getElementById('total_received_amount').value) || 0; // Get total received amount
    const percentageInput = inputElement.closest('.row').querySelector('.percentage-input'); // Get the corresponding percentage input

    // Calculate the percentage based on the amount relative to the total received amount
    const percentage = ((amount / totalReceivedAmount) * 100).toFixed(2);
    percentageInput.value = percentage; // Update the percentage field

    // Update total percentage on change
    updateTotalPercentage();
}

// Function to add more expense fields
document.getElementById('add-more-expenses').addEventListener('click', function() {
    const container = document.getElementById('additional-expenses-container');
    const newExpenseDiv = document.createElement('div');
    newExpenseDiv.className = 'row mb-2';
    newExpenseDiv.id = `additional-expense-${expenseCount}`;

    // Create new expense fields
    newExpenseDiv.innerHTML = `
        <div class="col-md-6">
            <input type="text" class="form-control" name="expense_descriptions[]" placeholder="Description">
        </div>
        <div class="col-md-4">
            <input type="number" class="form-control percentage-input" name="expense_percentages[]" placeholder="Percentage" step="0.01" oninput="calculateExpenseAmount(this); updateTotalPercentage()">
        </div>
        <div class="col-md-2">
            <input type="text" class="form-control amount-display" placeholder="Amount" oninput="calculatePercentage(this);" />
        </div>
    `;

    // Append new expense fields to the container
    container.appendChild(newExpenseDiv);
    expenseCount++; // Increment the expense counter
});

// Add event listeners for partner checkboxes to update total percentage
const partnerCheckboxes = document.querySelectorAll('.partner-checkbox');
partnerCheckboxes.forEach(checkbox => {
    checkbox.addEventListener('change', updateTotalPercentage);
});

</script>
<script>
    // Function to update the total cash value and remaining cash value
function updateTotalCashValue() {
    const cashValuePercentage = parseFloat(document.getElementById('cash_value_percentage').value) || 0; // Get cash value percentage
    const cashValueAmount = parseFloat(document.getElementById('cash_value_amount').value) || 0; // Get cash value amount
    const additionalAmounts = document.querySelectorAll('#additional-amounts-container input[type="text"]');
    
    // Calculate the total cash value (including additional amounts)
    let totalCashValue = cashValueAmount; // Start with the cash value amount

    // Add all additional amounts
    additionalAmounts.forEach(input => {
        const additionalAmount = parseFloat(input.value) || 0;
        totalCashValue += additionalAmount; // Add to total cash value
    });

    // Update the Total Cash Value field
    document.getElementById('total_cash_value').value = totalCashValue.toFixed(2); // Display total cash value

    // Now calculate the Remaining Cash Value
    const totalReceivedAmount = parseFloat(document.getElementById('total_received_amount').value) || 0; // Get total received amount
    const remainingCashValue = totalCashValue - totalReceivedAmount; // Calculate remaining cash value
    
    // Update the Remaining Cash Value field
    document.getElementById('remaining_cash_value').value = remainingCashValue.toFixed(2); // Display remaining cash value
}

// Add event listeners to update values on input change
document.getElementById('cash_value_percentage').addEventListener('input', updateTotalCashValue);
document.getElementById('cash_value_amount').addEventListener('input', updateTotalCashValue);
document.getElementById('total_received_amount').addEventListener('input', updateTotalCashValue);

// Also update the total cash value when additional amounts change
const additionalAmountInputs = document.querySelectorAll('#additional-amounts-container input[type="text"]');
additionalAmountInputs.forEach(input => {
    input.addEventListener('input', updateTotalCashValue);
});

</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Function to calculate the cheque value
        function calculateChequeValue() {
            const finalAmount = parseFloat(document.getElementById('final_amount').value) || 0;
            const totalCashValue = parseFloat(document.getElementById('total_cash_value').value) || 0;
            const chequeValue = finalAmount - totalCashValue;

            // Update the cheque value display
            document.getElementById('chequeValue').innerText = chequeValue.toFixed(2);
        }

        // Event listeners for when values change
        document.getElementById('final_amount').addEventListener('input', calculateChequeValue);
        document.getElementById('total_cash_value').addEventListener('input', calculateChequeValue);

        // Initialize calculation on page load
        calculateChequeValue();
    });

</script>

<script>
    function updateTotalCashValue() {
    let baseCashValue = parseFloat(document.getElementById('cash_value_amount').value) || 0;
    let additionalAmounts = document.querySelectorAll('.additional-amount');
    let totalAdditionalAmount = 0;

    // Sum up all additional amounts
    additionalAmounts.forEach(amountField => {
        totalAdditionalAmount += parseFloat(amountField.value) || 0;
    });

    // Calculate total cash value
    let totalCashValue = baseCashValue + totalAdditionalAmount;
    document.getElementById('total_cash_value').value = totalCashValue.toFixed(2);

    // Calculate the total cheque value
    calculateChequeValue();  // Call the function to update cheque value
}

function calculateChequeValue() {
    let finalAmount = parseFloat(document.getElementById('final_amount').value) || 0;
    let totalCashValue = parseFloat(document.getElementById('total_cash_value').value) || 0;

    // Subtract Total Cash Value from Final Amount to get the cheque value
    let chequeValue = finalAmount - totalCashValue;

    // Update the Total Cheque Value field
    document.getElementById('total_cheque_value').value = chequeValue.toFixed(2);
}

</script>

<script>
let baseChequeValue = 0; // This will be updated based on finalAmount and totalCashValue
let totalChequeValueWithAdditional = 0;

function calculateChequeValue() {
    const finalAmount = parseFloat(document.getElementById('final_amount').value) || 0;
    const totalCashValue = parseFloat(document.getElementById('total_cash_value').value) || 0;

    // Calculate the base cheque value
    baseChequeValue = finalAmount - totalCashValue;

    // Update the Total Cheque Value field
    document.getElementById('total_cheque_value').value = baseChequeValue.toFixed(2);

    // Update the total cheque value with additional expenses
    calculateTotalChequeValueWithAdditional();
}

document.getElementById('add-expense').addEventListener('click', function() {
    const expenseContainer = document.getElementById('expense-container');
    const newExpenseEntry = document.createElement('div');
    newExpenseEntry.classList.add('row', 'mb-2'); // Added Bootstrap classes for spacing
    newExpenseEntry.innerHTML = `
        <div class="col-md-6">
            <input type="text" placeholder="Expense Description" class="form-control expense-description" />
        </div>
        <div class="col-md-6">
            <input type="number" placeholder="Expense Amount" class="form-control expense-amount" oninput="calculateTotalChequeValueWithAdditional()" />
        </div>
    `;
    expenseContainer.appendChild(newExpenseEntry);
});

function calculateTotalChequeValueWithAdditional() {
    const expenseAmounts = document.querySelectorAll('.expense-amount');
    let totalExpenses = 0;

    expenseAmounts.forEach(amount => {
        totalExpenses += parseFloat(amount.value) || 0;
    });

    // Calculate the total cheque value with additional expenses
    totalChequeValueWithAdditional = baseChequeValue + totalExpenses;

    document.getElementById('total_cheque_value_with_additional').value = totalChequeValueWithAdditional.toFixed(2);
}

</script>
<script>

document.getElementById('gst_percentage').addEventListener('input', calculateGST);

function calculateGST() {
        const gstPercentage = parseFloat(document.getElementById('gst_percentage').value) || 0;
        const totalChequeValueWithAdditional = parseFloat(document.getElementById('total_cheque_value_with_additional').value) || 0;

        // Calculate the GST amount
        const gstAmount = (totalChequeValueWithAdditional * gstPercentage) / 100;

        // Update the GST Amount field
        document.getElementById('gst_amount').value = gstAmount.toFixed(2);

        // Calculate and update the Total Cheque Value (with GST)
        const totalChequeValueWithGST = totalChequeValueWithAdditional + gstAmount;
        document.getElementById('total_cheque_value_with_gst').value = totalChequeValueWithGST.toFixed(2);

        calculateGrandTotal();
    }


     function calculateTotalChequeValueWithAdditional() {
        const expenseAmounts = document.querySelectorAll('.expense-amount');
        let totalExpenses = 0;

        expenseAmounts.forEach(amount => {
            totalExpenses += parseFloat(amount.value) || 0;
        });

        // Calculate the total cheque value with additional expenses
        totalChequeValueWithAdditional = baseChequeValue + totalExpenses;

        document.getElementById('total_cheque_value_with_additional').value = totalChequeValueWithAdditional.toFixed(2);
        
        // Also recalculate the GST amount when expenses change
        calculateGST();
        calculateGrandTotal();
    }

    function calculateBalance() {
    const totalChequeValueWithGst = parseFloat(document.getElementById('total_cheque_value_with_gst').value) || 0;
    const receivedChequeValue = parseFloat(document.getElementById('received_cheque_value').value) || 0;

    // Calculate the balance
    const balanceAmount = totalChequeValueWithGst - receivedChequeValue;

    // Update the balance amount field
    document.getElementById('balance_amount').value = balanceAmount.toFixed(2);

    // Show or hide loan type selection based on balance amount
    const loanTypeContainer = document.getElementById('loan-type-container');
    if (balanceAmount !== 0) {
        loanTypeContainer.style.display = 'block'; // Show loan type selection
    } else {
        loanTypeContainer.style.display = 'none'; // Hide loan type selection
        document.getElementById('other-loan-description-container').style.display = 'none'; // Hide description field if balance is 0
    }
}

function handleLoanTypeChange() {
    const loanType = document.getElementById('loan_type').value;
    const otherLoanDescriptionContainer = document.getElementById('other-loan-description-container');
    const installmentContainer = document.getElementById('installment-container');

    // Show or hide the description field based on loan type selection
    if (loanType === 'others') {
        otherLoanDescriptionContainer.style.display = 'block';  // Show description field for "Others"
        installmentContainer.style.display = 'none';            // Hide installment fields for "Others"
    } else if (loanType !== "") {
        otherLoanDescriptionContainer.style.display = 'none';   // Hide description field
        installmentContainer.style.display = 'block';           // Show installment fields for valid loan types
    } else {
        otherLoanDescriptionContainer.style.display = 'none';   // Hide description field if no loan type is selected
        installmentContainer.style.display = 'none';            // Hide installment fields if no loan type is selected
    }
}

function calculateInstallmentAmount() {
    const balanceAmount = parseFloat(document.getElementById('balance_amount').value) || 0;
    const noOfInstallments = parseInt(document.getElementById('no_of_installments').value) || 1;

    // Calculate installment amount
    const installmentAmount = (balanceAmount / noOfInstallments).toFixed(2);

    // Update the Installment Amount field
    document.getElementById('installment_amount').value = installmentAmount;
}

function calculateBalance() {
    const totalChequeValueWithGst = parseFloat(document.getElementById('total_cheque_value_with_gst').value) || 0;
    const receivedChequeValue = parseFloat(document.getElementById('received_cheque_value').value) || 0;

    // Calculate the balance
    const balanceAmount = totalChequeValueWithGst - receivedChequeValue;

    // Update the balance amount field
    document.getElementById('balance_amount').value = balanceAmount.toFixed(2);

    // Show or hide loan type selection and installment options based on balance amount
    const loanTypeContainer = document.getElementById('loan-type-container');
    if (balanceAmount !== 0) {
        loanTypeContainer.style.display = 'block'; // Show loan type selection
    } else {
        loanTypeContainer.style.display = 'none';  // Hide loan type selection
        document.getElementById('installment-container').style.display = 'none'; // Hide installment fields if balance is 0
        document.getElementById('other-loan-description-container').style.display = 'none'; // Hide description field if balance is 0
    }
}

function calculateGrandTotal() {
    // Get values from the fields, ensuring they're treated as numbers
    const totalChequeValueWithAdditional = parseFloat(document.getElementById('total_cheque_value_with_additional').value) || 0;
    const totalChequeValueWithGst = parseFloat(document.getElementById('total_cheque_value_with_gst').value) || 0;

    // Calculate the grand total amount
    const grandTotalAmount = totalChequeValueWithAdditional + totalChequeValueWithGst;

    console.log("Total Cheque Value with Additional Amounts:", totalChequeValueWithAdditional);
    console.log("Total Cheque Value with GST:", totalChequeValueWithGst);
    console.log("Grand Total Amount:", grandTotalAmount);

    // Update the Grand Total Amount field
    document.getElementById('grand_total_amount').value = grandTotalAmount.toFixed(2);
}

// Add event listeners to update the grand total amount when input changes
document.getElementById('total_cheque_value_with_additional').addEventListener('input', calculateGrandTotal);
document.getElementById('total_cheque_value_with_gst').addEventListener('input', calculateGrandTotal);

</script>
