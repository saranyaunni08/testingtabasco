@extends('layouts.default', ['title' => 'Rooms'])

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="row p-4">
                       
                        @php
                            $roomTypes = [
                                'Flat' => [],
                                'Shops' => [],
                                'Car parking' => [],
                                'Table space' => [],
                                'Chair space' => [],
                                'Kiosk' => []
                            ];

                            foreach ($rooms as $room) {
                                $roomTypes[$room->room_type][] = $room;
                            }
                        @endphp

                        @foreach ($roomTypes as $type => $typeRooms)
                            @if (count($typeRooms) > 0)
                                <div class="table-responsive mb-4">
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                            <tr>
                                                <th colspan="10" class="bg-gradient-info text-white text-center font-weight-bold">{{ $type }}</th>
                                            </tr>
                                            <tr>
                                                <!-- Adjust headers based on $type -->
                                                @if ($type === 'Flat')
                                                    <th>Sl. No</th>
                                                    <th>Room Number</th>
                                                    <th>Flat Model</th>
                                                    <th>Carpet Area in sq</th>
                                                    <th>Carpet area price(sq)</th>
                                                    <th>Expected Amount</th>
                                                    <th>Actions</th>
                                                @elseif ($type === 'Shops')
                                                    <th>Sl. No</th>
                                                    <th>Room Number</th>
                                                    <th>Shop Type</th>
                                                    <th>Shop Area</th>
                                                    <th>Shop Rate</th>
                                                    <th>Expected Shop Amount</th>
                                                    <th>Actions</th>
                                                @elseif ($type === 'Car parking')
                                                    <th>Sl. No</th>
                                                    <th>Room Number</th>
                                                    <th>Parking Number</th>
                                                    <th>Parking Type</th>
                                                    <th>Parking Area</th>
                                                    <th>Parking Rate (per sq ft)</th>
                                                    <th>Total Square Feet</th>
                                                    <th>Actions</th>
                                                @elseif ($type === 'Table space')
                                                    <th>Sl. No</th>
                                                    <th>Room Number</th>
                                                    <th>Space Name</th>
                                                    <th>Space Type</th>
                                                    <th>Space Area</th>
                                                    <th>Space Rate</th>
                                                    <th>Expected Amount</th>
                                                    <th>Actions</th>
                                                @elseif ($type === 'Chair space')
                                                    <th>Sl. No</th>
                                                    <th>Room Number</th>
                                                    <th>Chair Name</th>
                                                    <th>Chair Type</th>
                                                    <th>Chair Material</th>
                                                    <th>Chair Price</th>
                                                    <th>Actions</th>
                                                @elseif ($type === 'Kiosk')
                                                    <th>Sl. No</th>
                                                    <th>Room Number</th>
                                                    <th>Kiosk Name</th>
                                                    <th>Kiosk Type</th>
                                                    <th>Kiosk Area</th>
                                                    <th>Kiosk Rate</th>
                                                    <th>Expected Amount</th>
                                                    <th>Actions</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($typeRooms as $index => $room)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $room->room_number }}</td>
                                                    <!-- Adjust columns based on $type -->
                                                    @if ($type === 'Flat')
                                                        <td style="text-transform: uppercase;">{{ $room->flat_model }}</td>
                                                        <td>{{ $room->flat_carpet_area }}</td>
                                                        <td>{{ $room->flat_carpet_area_price }}</td>
                                                        <td>{{ $room->flat_expected_carpet_area_price }}</td>
                                                    @elseif ($type === 'Shops')
                                                        <td style="text-transform: uppercase;">{{ $room->shop_type }}</td>
                                                        <td>{{ $room->carpet_area }}</td>
                                                        <td>{{ $room->carpet_area_price }}</td>
                                                        <td>{{ $room->expected_carpet_area_price }}</td>
                                                    @elseif ($type === 'Car parking')
                                                        <td>{{ $room->parking_number }}</td>
                                                        <td>{{ $room->parking_type }}</td>
                                                        <td>{{ $room->parking_area }}</td>
                                                        <td>{{ $room->parking_rate }}</td>
                                                        <td>
                                                            <div id="parking_rate_per_sq_ft_group{{ $room->id }}" class="d-none">
                                                                <label for="parking_rate_per_sq_ft">Parking Rate (per sq ft)</label>
                                                                <input type="number" class="form-control" id="parking_rate_per_sq_ft" name="parking_rate_per_sq_ft" required>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div id="total_sq_ft_group{{ $room->id }}" class="d-none">
                                                                <label for="total_sq_ft">Total Square Feet</label>
                                                                <input type="number" class="form-control" id="total_sq_ft" name="total_sq_ft">
                                                            </div>
                                                        </td>
                                                    @elseif ($type === 'Table space')
                                                        <td>{{ $room->space_name }}</td>
                                                        <td>{{ $room->space_type }}</td>
                                                        <td>{{ $room->space_area }}</td>
                                                        <td>{{ $room->space_rate }}</td>
                                                        <td>{{ $room->space_expected_price }}</td>
                                                    @elseif ($type === 'Chair space')
                                                        <td>{{ $room->chair_name }}</td>
                                                        <td>{{ $room->chair_type }}</td>
                                                        <td>{{ $room->chair_material }}</td>
                                                        <td>{{ $room->chair_price }}</td>
                                                    @elseif ($type === 'Kiosk')
                                                        <td>{{ $room->kiosk_name }}</td>
                                                        <td>{{ $room->kiosk_type }}</td>
                                                        <td>{{ $room->kiosk_area }}</td>
                                                        <td>{{ $room->kiosk_rate }}</td>
                                                        <td>{{ $room->kiosk_area * $room->kiosk_rate }}</td>
                                                    @endif
                                                    <td>
                                                        @if ($room->status === 'available')
                                                            <button type="button" class="btn btn-success btn-sm me-2" data-toggle="modal" data-target="#sellModal{{ $room->id }}" data-room-type="{{ $type }}" data-room-number="{{ $room->room_number }}">
                                                                Sell
                                                            </button>
                                                            <a href="{{ route('admin.rooms.edit', $room->id) }}" class="btn btn-warning btn-sm me-2">Edit</a>
                                                            <form action="{{ route('admin.rooms.destroy', ['building_id' => $building->id, 'room_id' => $room->id]) }}" method="POST" style="display: inline-block;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                            </form>
                                                        @else
                                                            <span style="color: #28a745; font-weight: bold; font-size: 1.2em; border: 2px solid #28a745; padding: 5px 10px; border-radius: 5px; background-color: #e9f7ef;">
                                                                Sold
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <div class="modal fade" id="sellModal{{ $room->id }}" tabindex="-1" aria-labelledby="sellModalLabel{{ $room->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
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
                                                                        <label for="area_calculation_type">Area Calculation Type</label>
                                                                        <select class="form-control" id="area_calculation_type" name="area_calculation_type" required>
                                                                            <option value="carpet_area_rate">Carpet Area Rate</option>
                                                                            <option value="built_up_area_rate">Super Built-up Area Rate</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="sale_amount">Sale Amount in sq</label>
                                                                        <input type="number" class="form-control" id="sale_amount" name="sale_amount" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="calculation_type">Calculation Type for Parking</label>
                                                                        <select class="form-control" id="calculation_type" name="calculation_type" required>
                                                                            <option value="fixed_amount">Unparked</option>
                                                                            <option value="rate_per_sq_ft">Rate per sq ft</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group d-none" id="parking_rate_per_sq_ft_group{{ $room->id }}">
                                                                        <label for="parking_rate_per_sq_ft">Parking Rate (per sq ft)</label>
                                                                        <input type="number" class="form-control" id="parking_rate_per_sq_ft" name="parking_rate_per_sq_ft">
                                                                    </div>
                                                                    <div class="form-group d-none" id="total_sq_ft_group{{ $room->id }}">
                                                                        <label for="total_sq_ft_for_parking">Total Square Feet</label>
                                                                        <input type="number" class="form-control" id="total_sq_ft_for_parking" name="total_sq_ft_for_parking">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="gst_percent">GST Percent</label>
                                                                        <input type="number" class="form-control" id="gst_percent" name="gst_percent" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="advance_payment">Total Advance Payment</label>
                                                                        <select class="form-control" id="advance_payment" name="advance_payment" required>
                                                                            <option value="now">Paying Now</option>
                                                                            <option value="later">Paying Later</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group d-none" id="advance_amount_group">
                                                                        <label for="advance_amount">Advance Amount</label>
                                                                        <input type="number" class="form-control" id="advance_amount" name="advance_amount">
                                                                    </div>
                                                                    <div class="form-group d-none" id="payment_method_group">
                                                                        <label for="payment_method">Payment Method</label>
                                                                        <select class="form-control" id="payment_method" name="payment_method">
                                                                            <option value="cash">Cash</option>
                                                                            <option value="bank_transfer">Bank Transfer</option>
                                                                            <option value="cheque">Cheque</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group d-none" id="transfer_id_group">
                                                                        <label for="transfer_id">Bank Transfer ID</label>
                                                                        <input type="text" class="form-control" id="transfer_id" name="transfer_id">
                                                                    </div>
                                                                    <div class="form-group d-none" id="cheque_id_group">
                                                                        <label for="cheque_id">Cheque ID</label>
                                                                        <input type="text" class="form-control" id="cheque_id" name="cheque_id">
                                                                    </div>
                                                                    <div class="form-group d-none" id="last_date_group">
                                                                        <label for="last_date">Last Date for Advance Payment</label>
                                                                        <input type="date" class="form-control" id="last_date" name="last_date">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="discount_percent">Discount (%)</label>
                                                                        <input type="number" class="form-control" id="discount_percent" name="discount_percent">
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                        <button type="submit" class="btn btn-primary">Sell Room</button>
                                                                    </div>
                                                                </form>
                                                                
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const modalElements = document.querySelectorAll('[id^="sellModal"]');
    
        modalElements.forEach((modalElement) => {
            const roomId = modalElement.id.replace('sellModal', '');
            const calculationTypeSelect = modalElement.querySelector('#calculation_type');
            const areaCalculationTypeSelect = modalElement.querySelector('#area_calculation_type');
            const buildUpAreaField = modalElement.querySelector('#build_up_area');
            const carpetAreaField = modalElement.querySelector('#carpet_area');
            const parkingRatePerSqFtGroup = modalElement.querySelector(`#parking_rate_per_sq_ft_group${roomId}`);
            const totalSqFtGroup = modalElement.querySelector(`#total_sq_ft_group${roomId}`);
            const advancePaymentSelect = modalElement.querySelector('#advance_payment');
            const advanceAmountGroup = modalElement.querySelector('#advance_amount_group');
            const paymentMethodGroup = modalElement.querySelector('#payment_method_group');
            const paymentMethodSelect = modalElement.querySelector('#payment_method');
            const transferIdGroup = modalElement.querySelector('#transfer_id_group');
            const chequeIdGroup = modalElement.querySelector('#cheque_id_group');
            const lastDateGroup = modalElement.querySelector('#last_date_group');
    
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
                if (areaCalculationTypeSelect && areaCalculationTypeSelect.value === 'build_up_area') {
                    if (buildUpAreaField) buildUpAreaField.classList.remove('d-none');
                    if (carpetAreaField) carpetAreaField.classList.add('d-none');
                } else {
                    if (buildUpAreaField) buildUpAreaField.classList.add('d-none');
                    if (carpetAreaField) carpetAreaField.classList.remove('d-none');
                }
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
                areaCalculationTypeSelect.addEventListener('change', toggleAreaCalculationFields);
            }
    
            // Initial setup based on current values
            toggleAdvancePaymentFields();
            togglePaymentMethodFields();
            toggleCalculationFields();
            toggleAreaCalculationFields();
        });
    });
    </script>
    
@endsection
