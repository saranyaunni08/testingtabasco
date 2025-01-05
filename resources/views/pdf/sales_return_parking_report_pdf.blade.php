<!DOCTYPE html>
<html>
<head>
    <title>Sales Return Parking Report PDF</title>
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
       <!-- PARKING SECTION -->
<div class="section-header">PARKING SALES REURN REPORT</div>
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
        @forelse ($parkingDetails->sortBy(fn($parking) => optional($parking->sale->parking)->floor_number) as $parking) <!-- Sorting logic added -->
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