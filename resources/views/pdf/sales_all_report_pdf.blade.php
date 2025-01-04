<!DOCTYPE html>
<html>
<head>
    <title>Sales Report PDF</title>
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
            <td colspan="7">COMMERCIAL SALES REPORT</td>
        </tr>
        <tr class="subheading">
            <th>FLOOR</th>
            <th>SHOP NO</th>
            <th>TYPE</th>
            <th>SQFT</th>
            <th>SALES PRICE</th>
            <th>TOTAL SALE AMOUNT</th>
            <th>CLIENT NAME</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($shopSalesData->sortBy('room_floor') as $row) <!-- Sort by room_floor -->
        <tr>
            <td>{{ $row->room_floor }}</td>
            <td>{{ $row->room_number }}</td>
            <td>{{ $row->room_type }}</td>
            <td>{{ number_format($row->build_up_area) }}</td>
            <td>{{ number_format(optional($row->sale)->sale_amount) }}</td>
            <td>{{ number_format(optional($row->sale)->total_amount, 2) }}</td>
            <td>{{ $row->customer_name }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" style="font-weight: bold;">TOTAL</td>
            <td>{{ number_format($totalShopSqft) }}</td>
            <td></td>
            <td>{{ number_format($totalShopSaleAmount) }}</td>
            <td></td>
        </tr>
    </tfoot>
</table>


 <!-- Apartment Sales Report -->
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
        @foreach ($apartmentSalesData->sortBy('room_floor') as $row) <!-- Sort by room_floor -->
        <tr>
            <td>{{ $row->room_floor }}</td>
            <td>{{ $row->room_number }}</td>
            <td>{{ $row->room_type }}</td>
            <td>{{ number_format($row->flat_build_up_area) }}</td>
            <td>{{ number_format($row->sale_amount) }}</td>
            <td>{{ number_format($row->total_amount, 2) }}</td>
            <td>{{ $row->customer_name }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" style="font-weight: bold;">TOTAL</td>
            <td>{{ number_format($totalApartmentSqft) }}</td>
            <td></td>
            <td>{{ number_format($totalApartmentSaleAmount) }}</td>
            <td></td>
        </tr>
    </tfoot>
</table>

<!-- Parking Sales Report -->
<table>
    <thead>
        <tr class="title-row">
            <td colspan="4">PARKING SALES REPORT</td>
        </tr>
        <tr class="subheading">
            <th>FLOOR</th>
            <th>PARKING NO</th>
            <th>SALES PRICE</th>
            <th>CLIENT NAME</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($parkingSalesData->sortBy('floor_number') as $sale) <!-- Sort by floor_number -->
        <tr>
            <td>{{ $sale->floor_number }}</td>
            <td>{{ $sale->parking_id }}</td>
            <td>{{ number_format($sale->sale_amount) }}</td>
            <td>{{ $sale->purchaser_name }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="1" style="font-weight: bold;">TOTAL</td>
            <td>{{ number_format($totalparkingnumber) }}</td>
            <td>{{ number_format($totalParkingSales) }}</td>
            <td></td>
        </tr>
    </tfoot>
</table>
</body>
</html>
