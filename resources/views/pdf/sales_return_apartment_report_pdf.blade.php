<!DOCTYPE html>
<html>
<head>
    <title>Sales Return Apartment Report PDF</title>
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

</body>
</html>