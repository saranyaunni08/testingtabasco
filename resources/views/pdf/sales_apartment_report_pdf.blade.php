<!DOCTYPE html>
<html>
<head>
    <title>Sales Apartment Report PDF</title>
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


    </style>
</head>
<body>
<table>
    <thead>
        <tr class="title-row">
            <td colspan="7">APARTMENT SALES REPORT</td>
        </tr>
        <tr class="subheading">
            <th>FLOOR</th>
            <th>DOOR NO</th>
            <th>TYPE</th>
            <th>SQFT</th>
            <th>SALES PRICE</th>
            <th>TOTAL SALE AMOUNT</th>
            <th>CLIENT NAME</th>
        </tr>
    </thead>
    <tbody>
        @php
            // Group the apartment sales data by floor
            $groupedByFloor = $apartmentSalesData->groupBy('room_floor');
        @endphp

        @foreach ($groupedByFloor as $floor => $floorData)
            @php
                // Calculate totals for the current floor
                $floorTotalSqft = $floorData->sum('flat_build_up_area');
                $floorTotalSaleAmount = $floorData->sum(function ($row) {
                    return optional($row->sale)->total_amount ?: 0;
                });
            @endphp

            <!-- Display floor-wise data -->
            @foreach ($floorData as $row)
                <tr>
                    <td>{{ $row->room_floor }}</td>
                    <td>{{ $row->room_number }}</td>
                    <td>{{ $row->room_type }}</td>
                    <td>{{ number_format($row->flat_build_up_area) }}</td>
                    <td>{{ number_format($row->sales_amount) }}</td>
                    <td>{{ number_format(optional($row->sale)->total_amount, 2) }}</td>
                    <td>{{ $row->customer_name }}</td>
                </tr>
            @endforeach

            <!-- Display total for the current floor -->
            <tr>
                <td colspan="3" style="font-weight: bold;">TOTAL</td>
                <td>{{ number_format($floorTotalSqft) }}</td>
                <td></td>
                <td>{{ number_format($floorTotalSaleAmount) }}</td>
                <td></td>
            </tr>
        @endforeach
    </tbody>
</table>


</body>
</html>
