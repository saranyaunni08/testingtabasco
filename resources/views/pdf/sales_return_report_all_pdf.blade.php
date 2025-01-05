<!DOCTYPE html>
<html>
<head>
    <title>Sales Return Report All PDF</title>
    <style>
    body {
        font-family: Arial, sans-serif;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    th, td {
        border: 1px solid #000; /* Solid black border for a more print-friendly appearance */
        padding: 8px;
        text-align: center;
    }

    th {
        font-weight: bold; /* Keep text bold in the header */
    }

    .title-row td {
        font-size: 20px;
        font-weight: bold;
        text-align: center;
        border: none; /* No border for title row */
        padding: 20px;
    }

    .subheading {
        font-weight: bold; /* Keep subheading bold */
    }

    /* Section Header Style */
    .section-header {
        font-size: 24px; /* Adjust font size for section header */
        font-weight: bold;
        text-align: center; /* Center align the text */
        margin-top: 20px; /* Add space above the section header */
        margin-bottom: 20px; /* Add space below the section header */
    }
</style>

</head>
<body>
    <!-- REPORT TITLE -->
    <div class="report-title">SALES RETURN REPORT</div>

    <!-- COMMERCIAL SECTION -->
    <div class="section-header">COMMERCIAL</div>
    @php
        $salesReturns = $salesReturns->sortBy(function ($salesReturn) {
            return optional($salesReturn->sale->room)->room_floor;
        });
    @endphp

    <table>
        <thead>
            <tr>
                <th>TYPE</th>
                <th>SHOP NO</th>
                <th>FLOOR</th>
                <th>SQFT</th>
                <th>SALES PRICE</th>
                <th>TOTAL SALE AMOUNT</th>
                <th>CLIENT NAME</th>
                <th>STATUS</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalSqft = 0;
                $totalSaleAmount = 0;
            @endphp
            @forelse($salesReturns as $salesReturn)
                    @if(optional($salesReturn->sale->room)->room_type === 'Shops')
                            @php
                                $room = $salesReturn->sale->room;
                                $totalSqft += $room->build_up_area ?? 0;
                                $totalSaleAmount += $salesReturn->sale->sale_amount ?? 0;
                            @endphp
                            <tr>
                                <td>{{ $room->room_type ?? '-' }}</td>
                                <td>{{ $room->room_number ?? '-' }}</td>
                                <td>{{ $room->room_floor ?? '-' }}</td>
                                <td>{{ number_format($room->build_up_area ?? 0) }}</td>
                                <td>{{ number_format($salesReturn->sale->sale_amount ?? 0) }}</td>
                                <td>{{ number_format($salesReturn->sale->total_amount ?? 0) }}</td>
                                <td>{{ $salesReturn->sale->customer_name ?? '-' }}</td>
                                <td>{{ $salesReturn->sale->status ?? '-' }}</td>
                            </tr>
                    @endif
            @empty
                <tr>
                    <td colspan="8">No commercial details available.</td>
                </tr>
            @endforelse
            @if($totalSqft > 0 && $totalSaleAmount > 0)
                <tr class="total-row">
                    <td colspan="3">Total</td>
                    <td>{{ number_format($totalSqft) }}</td>
                    <td></td>
                    <td>{{ number_format($totalSaleAmount) }}</td>
                    <td></td>
                    <td></td>
                </tr>
            @endif
        </tbody>
    </table>


    <!-- APARTMENTS SECTION -->
    <div class="section-header">APARTMENTS</div>
    @php
        // Sorting the salesFlats collection by room_floor in ascending order
        $salesFlats = $salesFlats->sortBy(function ($flat) {
            return optional($flat->sale->room)->room_floor;
        });
    @endphp

    <table>
        <thead>
            <tr>
                <th>TYPE</th>
                <th>DOOR NO</th>
                <th>FLOOR</th>
                <th>SQFT</th>
                <th>SALES PRICE</th>
                <th>TOTAL SALE AMOUNT</th>
                <th>CLIENT NAME</th>
                <th>STATUS</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalSqft = 0;
                $totalSaleAmount = 0;
            @endphp
            @forelse ($salesFlats as $flat)
                        @php
                            $room = $flat->sale->room ?? null;
                            $sqft = $room->flat_build_up_area ?? 0;
                            $salePrice = $flat->sale->sale_amount ?? 0;
                            $calculatedTotal = $flat->sale->total_amount ?? 0;

                            $totalSqft += $sqft;
                            $totalSaleAmount += $calculatedTotal;
                        @endphp
                        <tr>
                            <td>{{ $room->room_type ?? 'N/A' }}</td>
                            <td>{{ $room->room_number ?? 'N/A' }}</td>
                            <td>{{ $room->room_floor ?? 'N/A' }}</td>
                            <td>{{ $sqft }}</td>
                            <td>{{ number_format($salePrice) }}</td>
                            <td>{{ number_format($calculatedTotal) }}</td>
                            <td>{{ $flat->sale->customer_name ?? 'N/A' }}</td>
                            <td>{{ $flat->sale->status ?? 'N/A' }}</td>
                        </tr>
            @empty
                <tr>
                    <td colspan="8">No sales flats found.</td>
                </tr>
            @endforelse
            @if(count($salesFlats) > 0)
                <tr class="total-row">
                    <td colspan="3">Total</td>
                    <td>{{ $totalSqft }}</td>
                    <td></td>
                    <td>{{ number_format($totalSaleAmount) }}</td>
                    <td></td>
                    <td></td>
                </tr>
            @endif
        </tbody>
    </table>

    
    <!-- KIOSKS SECTION -->
    <div class="section-header">KIOSKS</div>

@php
    // Sorting the salesKiosks collection by room_floor in ascending order
    $salesKiosks = $salesKiosks->sortBy(function ($kiosk) {
        return optional($kiosk->sale->room)->room_floor;
    });
@endphp

<table>
    <thead>
        <tr>
            <th>TYPE</th>
            <th>DOOR NO</th>
            <th>FLOOR</th>
            <th>SQFT</th>
            <th>SALES PRICE</th>
            <th>TOTAL SALE AMOUNT</th>
            <th>CLIENT NAME</th>
            <th>STATUS</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalSqft = 0;
            $totalSaleAmount = 0;
        @endphp
        @forelse ($salesKiosks as $kiosk)
            @php
                $room = $kiosk->sale->room ?? null;
                $sqft = $room->kiosk_area ?? 0; // Using kiosk_area instead of flat_build_up_area
                $salePrice = $kiosk->sale->sale_amount ?? 0;
                $calculatedTotal = $kiosk->sale->total_amount ?? 0;

                $totalSqft += $sqft;
                $totalSaleAmount += $calculatedTotal;
            @endphp
            <tr>
                <td>{{ $room->room_type ?? 'N/A' }}</td>
                <td>{{ $room->room_number ?? 'N/A' }}</td>
                <td>{{ $room->room_floor ?? 'N/A' }}</td>
                <td>{{ $sqft }}</td>
                <td>{{ number_format($salePrice) }}</td>
                <td>{{ number_format($calculatedTotal) }}</td>
                <td>{{ $kiosk->sale->customer_name ?? 'N/A' }}</td>
                <td>{{ $kiosk->sale->status ?? 'N/A' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8">No kiosks details found.</td>
            </tr>
        @endforelse
        @if(count($salesKiosks) > 0)
            <tr class="total-row">
                <td colspan="3">Total</td>
                <td>{{ $totalSqft }}</td>
                <td></td>
                <td>{{ number_format($totalSaleAmount) }}</td>
                <td></td>
                <td></td>
            </tr>
        @endif
    </tbody>
</table>

<div class="section-header">TABLESPACES</div>

@php
    // Sorting the salesTablespaces collection by room_floor in ascending order
    $salesTablespaces = $salesTablespaces->sortBy(function ($tablespace) {
        return optional($tablespace->sale->room)->room_floor;
    });
@endphp

<table>
    <thead>
        <tr>
            <th>TYPE</th>
            <th>DOOR NO</th>
            <th>FLOOR</th>
            <th>SQFT</th>
            <th>SALES PRICE</th>
            <th>TOTAL SALE AMOUNT</th>
            <th>CLIENT NAME</th>
            <th>STATUS</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalSqft = 0;
            $totalSaleAmount = 0;
        @endphp
        @forelse ($salesTablespaces as $tablespace)
            @php
                $room = $tablespace->sale->room ?? null;
                $sqft = $room->space_area ?? 0; // Using space_area instead of flat_build_up_area
                $salePrice = $tablespace->sale->sale_amount ?? 0;
                $calculatedTotal = $tablespace->sale->total_amount ?? 0;

                $totalSqft += $sqft;
                $totalSaleAmount += $calculatedTotal;
            @endphp
            <tr>
                <td>{{ $room->room_type ?? 'N/A' }}</td>
                <td>{{ $room->room_number ?? 'N/A' }}</td>
                <td>{{ $room->room_floor ?? 'N/A' }}</td>
                <td>{{ $sqft }}</td>
                <td>{{ number_format($salePrice) }}</td>
                <td>{{ number_format($calculatedTotal) }}</td>
                <td>{{ $tablespace->sale->customer_name ?? 'N/A' }}</td>
                <td>{{ $tablespace->sale->status ?? 'N/A' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8">No tablespaces details found.</td>
            </tr>
        @endforelse
        @if(count($salesTablespaces) > 0)
            <tr class="total-row">
                <td colspan="3">Total</td>
                <td>{{ $totalSqft }}</td>
                <td></td>
                <td>{{ number_format($totalSaleAmount) }}</td>
                <td></td>
                <td></td>
            </tr>
        @endif
    </tbody>
</table>

<div class="section-header">CHAIRSPACES</div>

@php
    // Sorting the salesChairspaces collection by room_floor in ascending order
    $salesChairspaces = $salesChairspaces->sortBy(function ($chairspace) {
        return optional($chairspace->sale->room)->room_floor;
    });
@endphp

<table>
    <thead>
        <tr>
            <th>TYPE</th>
            <th>DOOR NO</th>
            <th>FLOOR</th>
            <th>SQFT</th>
            <th>SALES PRICE</th>
            <th>TOTAL SALE AMOUNT</th>
            <th>CLIENT NAME</th>
            <th>STATUS</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalSqft = 0;
            $totalSaleAmount = 0;
        @endphp
        @forelse ($salesChairspaces as $chairspace)
            @php
                $room = $chairspace->sale->room ?? null;
                $sqft = $room->chair_space_in_sq ?? 0; // Using chair_space_in_sq for chairspace area
                $salePrice = $chairspace->sale->sale_amount ?? 0;
                $calculatedTotal = $chairspace->sale->total_amount ?? 0;

                $totalSqft += $sqft;
                $totalSaleAmount += $calculatedTotal;
            @endphp
            <tr>
                <td>{{ $room->room_type ?? 'N/A' }}</td>
                <td>{{ $room->room_number ?? 'N/A' }}</td>
                <td>{{ $room->room_floor ?? 'N/A' }}</td>
                <td>{{ $sqft }}</td>
                <td>{{ number_format($salePrice) }}</td>
                <td>{{ number_format($calculatedTotal) }}</td>
                <td>{{ $chairspace->sale->customer_name ?? 'N/A' }}</td>
                <td>{{ $chairspace->sale->status ?? 'N/A' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8">No chairspaces details found.</td>
            </tr>
        @endforelse
        @if(count($salesChairspaces) > 0)
            <tr class="total-row">
                <td colspan="3">Total</td>
                <td>{{ $totalSqft }}</td>
                <td></td>
                <td>{{ number_format($totalSaleAmount) }}</td>
                <td></td>
                <td></td>
            </tr>
        @endif
    </tbody>
</table>

<div class="section-header">CUSTOM SPACES</div>

@php
    // Sorting the salesCustomspaces collection by room_floor in ascending order
    $salesCustomspaces = $salesCustomspaces->sortBy(function ($customspace) {
        return optional($customspace->sale->room)->room_floor;
    });
@endphp

<table>
    <thead>
        <tr>
            <th>TYPE</th>
            <th>DOOR NO</th>
            <th>FLOOR</th>
            <th>SQFT</th>
            <th>SALES PRICE</th>
            <th>TOTAL SALE AMOUNT</th>
            <th>CLIENT NAME</th>
            <th>STATUS</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalSqft = 0;
            $totalSaleAmount = 0;
        @endphp
        @forelse ($salesCustomspaces as $customspace)
            @php
                $room = $customspace->sale->room ?? null;
                $sqft = $room->custom_area ?? 0; // Using custom_area instead of chair_space_in_sq
                $salePrice = $customspace->sale->sale_amount ?? 0;
                $calculatedTotal = $customspace->sale->total_amount ?? 0;

                $totalSqft += $sqft;
                $totalSaleAmount += $calculatedTotal;
            @endphp
            <tr>
                <td>{{ $room->room_type ?? 'N/A' }}</td>
                <td>{{ $room->room_number ?? 'N/A' }}</td>
                <td>{{ $room->room_floor ?? 'N/A' }}</td>
                <td>{{ $sqft }}</td>
                <td>{{ number_format($salePrice) }}</td>
                <td>{{ number_format($calculatedTotal) }}</td>
                <td>{{ $customspace->sale->customer_name ?? 'N/A' }}</td>
                <td>{{ $customspace->sale->status ?? 'N/A' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8">No custom spaces details found.</td>
            </tr>
        @endforelse
        @if(count($salesCustomspaces) > 0)
            <tr class="total-row">
                <td colspan="3">Total</td>
                <td>{{ $totalSqft }}</td>
                <td></td>
                <td>{{ number_format($totalSaleAmount) }}</td>
                <td></td>
                <td></td>
            </tr>
        @endif
    </tbody>
</table>






    <!-- PARKING SECTION -->
    <div class="section-header">PARKING</div>
    @php
        // Sorting the parkingDetails collection by floor_number in ascending order
        $parkingDetails = $parkingDetails->sortBy(function ($parking) {
            return optional($parking->sale->parking)->floor_number;
        });
    @endphp

    <table>
        <thead>
            <tr>
                <th>TYPE</th>
                <th>PARKING NO</th>
                <th>FLOOR</th>
                <th>SALES PRICE</th>
                <th>CLIENT NAME</th>
                <th>STATUS</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalSalesAmount = 0;
            @endphp
            @forelse ($parkingDetails as $parking)
                        @php
                            $sale = $parking->sale ?? null;
                            $parkingData = $sale->parking ?? null;
                            $salesAmount = ($sale->parking_amount_cash ?? 0) + ($sale->parking_amount_cheque ?? 0);

                            $totalSalesAmount += $salesAmount;
                        @endphp
                        <tr>
                            <td>Parking</td>
                            <td>{{ $parkingData->slot_number ?? 'N/A' }}</td>
                            <td>{{ $parkingData->floor_number ?? 'N/A' }}</td>
                            <td>{{ number_format($salesAmount) }}</td>
                            <td>{{ $sale->customer_name ?? 'N/A' }}</td>
                            <td>{{ $sale->status ?? 'N/A' }}</td>
                        </tr>
            @empty
                <tr>
                    <td colspan="6">No parking details available.</td>
                </tr>
            @endforelse
            <tr class="total-row">
                <td colspan="3"><strong>Total</strong></td>
                <td>{{ number_format($totalSalesAmount) }}</td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>
</body>
</html>