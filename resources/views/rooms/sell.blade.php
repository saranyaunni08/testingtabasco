@extends('layouts.default')

@section('content')
<div class="container">
    <h1>Sell Room</h1>

    <form id="sellRoomForm" action="{{ route('admin.sales.store') }}" method="POST">
        @csrf
        <input type="hidden" id="room_type" value="{{ $room->room_type }}">


        <!-- Customer Information -->
        <div class="form-group">
            <label for="customer_name">Customer Name</label>
            <input type="text" class="form-control" id="customer_name" name="customer_name" required>
        </div>

        <div class="form-group">
            <label for="customer_email">Customer Email</label>
            <input type="email" class="form-control" id="customer_email" name="customer_email" required>
        </div>

        <div class="form-group">
            <label for="customer_contact">Customer Contact</label>
            <input type="text" class="form-control" id="customer_contact" name="customer_contact" required>
        </div>

            <div class="form-group">
                <label class="font-weight-bold" for="sale_amount">Sale Amount in sq</label>
                <input type="number" class="form-control" id="sale_amount" name="sale_amount" oninput="calculateSoldAmount()" required>
            </div>

            <label for="calculation_type">Area Calculation Type</label>
            <select class="form-control" id="area_calculation_type" name="area_calculation_type" required>
                <option value="" selected disabled>Select</option>
                <option value="carpet_area_rate">Carpet Area</option>
                <option value="built_up_area_rate">Super Built-up Area</option>
            </select>
            
        @if($room->room_type == 'Flat')
            <div class="form-group">
                <label class="font-weight-bold" for="flat_build_up_area">Super Build-Up Area in sq</label>
                <input type="text" class="form-control" id="flat_build_up_area" name="flat_build_up_area" value="{{ $room->flat_build_up_area }}" readonly>
            </div>
            <div class="form-group">
                <label class="font-weight-bold" for="flat_carpet_area">Carpet Area in sq</label>
                <input type="text" class="form-control" id="flat_carpet_area" name="flat_carpet_area" value="{{ $room->flat_carpet_area }}" readonly>
            </div>
    @endif  

    @if($room->room_type == 'Shops')
        <div class="form-group">
            <label class="font-weight-bold" for="build_up_area">Super Build-Up Area</label>
            <input type="text" class="form-control" id="build_up_area" name="build_up_area" value="{{ $room->build_up_area }}" readonly>
        </div>
        <div class="form-group">
            <label class="font-weight-bold" for="carpet_area">Carpet Area in sq</label>
            <input type="text" class="form-control" id="carpet_area" name="carpet_area" value="{{ $room->carpet_area }}" readonly>
    </div>
@endif  
    @if($room->room_type == 'Table space')
        <div class="form-group">
            <label class="font-weight-bold" for="space_area">Super Build-Up Area in sq</label>
            <input type="text" class="form-control" id="space_area" name="space_area" value="{{ $room->space_area }}" readonly>
        </div>
@endif  
    @if($room->room_type == 'Chair space')
        <div class="form-group">
            <label class="font-weight-bold" for="chair_space_in_sq">Super Build-Up Area in sq</label>
            <input type="text" class="form-control" id="chair_space_in_sq" name="chair_space_in_sq" value="{{ $room->chair_space_in_sq }}" readonly>
        </div>
@endif  
    @if($room->room_type == 'Kiosk')
        <div class="form-group">
            <label class="font-weight-bold" for="kiosk_area">Super Build-Up Area in sq</label>
            <input type="text" class="form-control" id="kiosk_area" name="kiosk_area" value="{{ $room->kiosk_area }}" readonly>
        </div>
@endif  

        <div class="form-group">
            <label for="sold_amount">Sold Amount</label>
            <input type="number" class="form-control" id="sold_amount" name="sold_amount" readonly>
        </div>


       
        <!-- Parking Information -->
        <div class="form-group">
            <label for="calculation_type">Calculation Type for Parking</label>
            <select class="form-control" id="calculation_type" name="calculation_type" required>
                <option value="no_parking">No Parking Needed</option>
                <option value="fixed">Fixed Parking</option>
            </select>
        </div>

        <div class="form-group" id="fixed_parking_amount_container" style="display:none;">
            <label for="fixed_parking_amount">Fixed Parking Amount</label>
            <input type="number" class="form-control" id="fixed_parking_amount" name="fixed_parking_amount">
        </div>

        <!-- Discount -->
        <div class="form-group">
            <label for="discount_percent">Discount (%)</label>
            <input type="number" class="form-control" id="discount_percent" name="discount_percent" value="0" required>
        </div>

        <div class="form-group">
            <label for="discount_amount">Discount Amount</label>
            <input type="number" class="form-control" id="discount_amount" name="discount_amount" readonly>
        </div>

        <!-- Total Cash Information -->
        <div class="form-group">
            <label for="cash_value_percent">Total Cash Value (%)</label>
            <input type="number" class="form-control" id="cash_value_percent" name="cash_value_percent" required>
        </div>

        <div class="form-group">
            <label for="cash_value_amount">Total Cash Amount</label>
            <input type="number" class="form-control" id="cash_value_amount" name="cash_value_amount" required>
        </div>

        <!-- Additional Cash Amounts -->
        <div class="form-group">
            <label for="add_other_amount">Add Other Amounts</label>
            <button type="button" class="btn btn-primary" id="add_other_amount_button">+</button>
        </div>

        <div id="other_amounts_container"></div>

        <!-- Received Cash Information -->
        <div class="form-group">
            <label for="received_cash_amount">Received Cash Amount</label>
            <input type="number" class="form-control" id="received_cash_amount" name="received_cash_amount" required>
        </div>

        <div class="form-group">
            <label for="received_cash_percent">Received Cash Percentage (%)</label>
            <input type="number" class="form-control" id="received_cash_percent" name="received_cash_percent" readonly>
        </div>

        <!-- Partner Distribution -->
        <div class="form-group">
            <label for="partners">Select Partners</label>
            <div id="partners_container">
                @foreach($partners as $partner)
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="partner_{{ $partner->id }}" data-partner-id="{{ $partner->id }}">
                        <label class="form-check-label" for="partner_{{ $partner->id }}">{{ $partner->first_name }}</label>
                        <div class="form-group percentage-container" id="percentage_container_{{ $partner->id }}" style="display:none;">
                            <label for="percentage_{{ $partner->id }}">Percentage</label>
                            <input type="number" class="form-control" id="percentage_{{ $partner->id }}" data-partner-id="{{ $partner->id }}" name="percentage_{{ $partner->id }}">
                            <label for="amount_{{ $partner->id }}">Amount</label>
                            <input type="number" class="form-control" id="amount_{{ $partner->id }}" name="amount_{{ $partner->id }}" readonly>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>


        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const saleAmountInput = document.getElementById('sale_amount');
        const areaCalculationTypeSelect = document.getElementById('area_calculation_type');
        const soldAmountInput = document.getElementById('sold_amount');

        const flatBuildUpAreaInput = document.getElementById('flat_build_up_area');
        const flatCarpetAreaInput = document.getElementById('flat_carpet_area');
        const buildUpAreaInput = document.getElementById('build_up_area');
        const carpetAreaInput = document.getElementById('carpet_area');
        const spaceAreaInput = document.getElementById('space_area');
        const chairSpaceInSqInput = document.getElementById('chair_space_in_sq');
        const kioskAreaInput = document.getElementById('kiosk_area');

        const flatBuildUpArea = flatBuildUpAreaInput ? parseFloat(flatBuildUpAreaInput.value) : 0;
        const flatCarpetArea = flatCarpetAreaInput ? parseFloat(flatCarpetAreaInput.value) : 0;
        const buildUpArea = buildUpAreaInput ? parseFloat(buildUpAreaInput.value) : 0;
        const carpetArea = carpetAreaInput ? parseFloat(carpetAreaInput.value) : 0;
        const spaceArea = spaceAreaInput ? parseFloat(spaceAreaInput.value) : 0;
        const chairSpaceInSq = chairSpaceInSqInput ? parseFloat(chairSpaceInSqInput.value) : 0;
        const kioskArea = kioskAreaInput ? parseFloat(kioskAreaInput.value) : 0;

        // Function to calculate the sold amount
        function calculateSoldAmount() {
            const saleAmount = parseFloat(saleAmountInput.value);
            console.log('Sale Amount:', saleAmount); // Log the sale amount to the console
            const areaType = areaCalculationTypeSelect.value;
            let result = 0;

            if (!isNaN(saleAmount)) {
                if (areaType === 'carpet_area_rate') {
                    if (flatCarpetArea) {
                        result = saleAmount * flatCarpetArea;
                    } else {
                        result = saleAmount * carpetArea;
                    }
                } else if (areaType === 'built_up_area_rate') {
                    if (flatBuildUpArea) {
                        result = saleAmount * flatBuildUpArea;
                    } else {
                        result = saleAmount * (buildUpArea || spaceArea || chairSpaceInSq || kioskArea);
                    }
                }
            }

            soldAmountInput.value = result.toFixed(2);
        }

        // Attach event listeners for input changes
        saleAmountInput.addEventListener('input', calculateSoldAmount);
        areaCalculationTypeSelect.addEventListener('change', calculateSoldAmount);
    });
</script>
@endsection
