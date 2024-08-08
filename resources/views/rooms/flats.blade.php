@extends('layouts.default', ['title' => 'Flats'])

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-info border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                        <h6 class="text-white text-capitalize ps-3">Room Details</h6>
                        <a href="{{ route('admin.rooms.create', ['building_id' => $building_id, 'room_type' => 'Flats']) }}" 
                           class="btn btn-outline-light float-end" 
                           style="background-color: #ffffff; border-color: #007bff; color: #007bff; font-weight: bold;" 
                           onmouseover="this.style.backgroundColor='#007bff'; this.style.color='#ffffff'" 
                           onmouseout="this.style.backgroundColor='#ffffff'; this.style.color='#007bff'">Add Flats</a>
                    </div>
                </div>

                <div class="card-body">
                    <h5 class="card-header">Flats</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" style="width:100%">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Room Floor</th>
                                    <th>Room Type</th>
                                    <th>Flat Model</th>
                                    <th>Flat Build Up Area</th>
                                    <th>Flat Build Up Area Price</th>
                                    <th>Flat Expected Super Build Area Price</th>
                                    <th>Flat Carpet Area</th>
                                    <th>Flat Carpet Area Price</th>
                                    <th>Flat Expected Carpet Area Price</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rooms as $room)
                                    <tr>
                                        <td>{{ $room->room_floor }}</td>
                                        <td>{{ $room->room_type }}</td>
                                        <td>{{ $room->flat_model }}</td>
                                        <td>{{ $room->flat_build_up_area }} sq ft</td>
                                        <td>{{ $room->flat_super_build_up_price }} sq ft</td>
                                        <td>₹{{ number_format($room->flat_expected_super_buildup_area_price, 2) }}</td>
                                        <td>{{ $room->flat_carpet_area }} sq ft</td>
                                        <td>{{ $room->flat_carpet_area_price }} sq ft</td>
                                        <td>₹{{ number_format($room->flat_expected_carpet_area_price, 2, '.', ',') }}</td>
                                        <td>
                                            @php
                                                $sale = $room->sales->first();
                                                $isPaid = $sale && $installments->where('sale_id', $sale->id)->where('status', 'sold')->isNotEmpty();
                                            @endphp
                        
                                            @if($room->status == 'available')
                                                <span class="badge badge-info">Available</span>
                                            @elseif($isPaid)
                                                <span class="badge badge-success">Paid</span>
                                            @else
                                                <span class="badge badge-danger">Booking</span>
                                            @endif
                                        </td>
                                        <td class="d-flex">
                                            @if($room->status == 'available' || $room->status == 'booking')
                                            <button type="button" class="btn btn-success btn-sm me-2" data-toggle="modal" data-target="#authModal{{ $room->id }}" data-building-id="{{ $room->building_id }}" data-room-id="{{ $room->id }}" data-action="edit">
                                                <i class="bx bx-edit bx-sm"></i>
                                            </button>
                                            <form action="{{ route('admin.rooms.destroy', ['roomId' => $room->id, 'buildingId' => $building_id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this room?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash-alt bx-sm"></i>
                                                </button>
                                            </form>
                                            
                                            @endif
                        
                                            @if ($room->status === 'available')
                                                <button type="button" class="btn btn-primary btn-sm me-2" data-toggle="modal" data-target="#sellModal{{ $room->id }}">
                                                    Sell
                                                </button>
                                            @elseif ($room->status === 'sold')
                                                @if ($room->sale && $room->sale->customer_name)
                                                    <a href="{{ route('admin.customers.show', ['customerName' => $room->sale->customer_name]) }}"
                                                        style="color: #28a745; font-weight: bold; font-size: 1.2em; border: 2px solid #28a745;
                                                        padding: 5px 10px; border-radius: 5px; background-color: #e9f7ef; text-decoration:none;">View
                                                    </a>
                                                @else
                                                    <button type="button" class="btn btn-secondary btn-sm me-2" disabled>
                                                        No Sale Info
                                                    </button>
                                                @endif
                                            @else
                                                <button type="button" class="btn btn-secondary btn-sm me-2" disabled>
                                                    Not Available
                                                </button>
                                            @endif
  <!-- Authentication Modal -->
<div class="modal fade" id="authModal{{ $room->id }}" tabindex="-1" aria-labelledby="authModalLabel{{ $room->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="authModalLabel{{ $room->id }}">Authenticate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="authForm{{ $room->id }}" action="{{ route('admin.edit_delete_auth.authenticate') }}" method="POST">
                    @csrf
                    <input type="hidden" name="redirect_url" id="redirectUrl{{ $room->id }}">
                    <input type="hidden" name="room_id" value="{{ $room->id }}">
                    <input type="hidden" name="building_id" value="{{ $room->building_id }}">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="container mt-4">
                        @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center">No records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($rooms as $room)
        @if ($room->status === 'available')
            <div class="modal fade" id="sellModal{{ $room->id }}" tabindex="-1" aria-labelledby="sellModalLabel{{ $room->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg"> 
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
                                    @if($room->room_type == 'Flat')
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold" for="flat_build_up_area">Super Build-Up Area Rate</label>
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
                                        <div class="form-group d-none" id="fixed_parking_amount_group{{ $room->id }}">
                                            <label class="font-weight-bold" for="fixed_parking_amount">Fixed Parking Amount</label>
                                            <input type="number" class="form-control" id="fixed_parking_amount" name="fixed_parking_amount">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold" for="notes">Notes</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Sell Room</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
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
        const gstAmountElement = modalElement.querySelector('#gst_amount'); 
    
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
                if (lastDateGroup) lastDateGroup.classList.add('d-none');
            } else if (advancePaymentSelect && advancePaymentSelect.value === 'later') {
                if (advanceAmountGroup) advanceAmountGroup.classList.add('d-none');
                if (paymentMethodGroup) paymentMethodGroup.classList.add('d-none');
                if (transferIdGroup) transferIdGroup.classList.add('d-none');
                if (chequeIdGroup) chequeIdGroup.classList.add('d-none');
                if (lastDateGroup) lastDateGroup.classList.remove('d-none');
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
                    console.log('Initial Total Rate:', totalRate);
    
                    let parkingAmount = 0;
                    if (calculationTypeSelect.value === 'rate_per_sq_ft') {
                        const parkingRate = parseFloat(parkingRateInput.value) || 0;
                        const totalSqFt = parseFloat(totalSqFtInput.value) || 0;
                        parkingAmount = parkingRate * totalSqFt;
                        totalRate += parkingAmount;
                        console.log('Parking Amount (rate_per_sq_ft):', parkingAmount);
                    } else if (calculationTypeSelect.value === 'fixed_amount') {
                        parkingAmount = 0;  
                        totalRate += parkingAmount;
                        console.log('Parking Amount (fixed_amount):', parkingAmount);
                    }
    
                    console.log('Total Rate after Parking:', totalRate);
    
                    const discountPercent = parseFloat(discountInput.value) || 0;
                    const discountAmount = totalRate * (discountPercent / 100);
                    totalRate -= discountAmount;
                    console.log('Discount Amount:', discountAmount);
                    console.log('Total Rate after Discount:', totalRate);
    
                    const advanceAmount = parseFloat(advanceAmountInput.value) || 0;
                    const remainingBalance = totalRate - advanceAmount;
                    if (remainingBalanceElement) remainingBalanceElement.textContent = remainingBalance.toFixed(2);
    
                    const cashInHandPercent = parseFloat(cashInHandPercentInput.value) || 0;
                    const cashInHandAmount = (cashInHandPercent / 100) * totalRate;
                    if (inHandAmountInput) inHandAmountInput.value = cashInHandAmount.toFixed(2);
    
                    const amountForGST = totalRate - cashInHandAmount;
                    console.log('Amount for GST:', amountForGST);
    
                    const gstPercent = parseFloat(gstInput.value) || 0;
                    const gstAmount = amountForGST * (gstPercent / 100);
                    console.log('GST Amount:', gstAmount);
    
                    const finalTotal = totalRate + gstAmount;
                    console.log('Final Total after GST:', finalTotal);
    
                    if (totalElement) totalElement.textContent = finalTotal.toFixed(2);
                    if (gstAmountElement) gstAmountElement.textContent = gstAmount.toFixed(2); 
                },
                error: function(error) {
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
    
    <script>
      document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('button[data-toggle="modal"]').forEach(button => {
        button.addEventListener('click', function() {
            const roomId = this.getAttribute('data-room-id');
            const buildingId = this.getAttribute('data-building-id');

            const redirectUrl = `{{ route('admin.rooms.destroy', ['roomId' => '__ROOM_ID__', 'buildingId' => '__BUILDING_ID__']) }}`
                .replace('__ROOM_ID__', roomId)
                .replace('__BUILDING_ID__', buildingId);

            document.getElementById('redirectUrl' + roomId).value = redirectUrl;
        });
    });
});

    </script>
    
    
    
@endsection