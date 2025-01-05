<!DOCTYPE html>
<html>
<head>
    <title>Sales Return Commercial Report PDF</title>
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
    color: black; /* No background or color for subheading */
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
   <!-- COMMERCIAL SECTION -->
   <div class="section-header">COMMERCIAL SALES RETURN REPORT</div>
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
        @forelse($salesReturns->sortBy(fn($salesReturn) => optional($salesReturn->sale->room)->room_floor) as $salesReturn) <!-- Sorting logic added -->
            @if(optional($salesReturn->sale->room)->room_type === 'shops')
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
                    <td>{{ number_format(($room->build_up_area ?? 0) * ($salesReturn->sale->sale_amount ?? 0), 2) }}</td>
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
</body>
</html>