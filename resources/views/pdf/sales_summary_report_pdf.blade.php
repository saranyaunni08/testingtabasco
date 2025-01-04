<!DOCTYPE html>
<html>
<head>
    <title>Sales Summary Report PDF</title>
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
        <!-- SALES SUMMARY Header Inside Table -->
        <tr>
            <th colspan="3" class="table-header">SALES SUMMARY</th>
        </tr>
        <!-- Column Headers -->
        <tr>
            <th>TYPE</th>
            <th>SQFT</th>
            <th>SALE AMOUNT</th>
        </tr>
    </thead>
    <tbody>
        <!-- Dynamic Data for SHOP -->
        <tr>
            <td>SHOP</td>
            <td>{{ number_format($totalShopSqft) }}</td>
            <td>{{ number_format($totalShopSaleAmount) }}</td>
        </tr>

        <!-- Dynamic Data for APARTMENTS -->
        <tr>
            <td>APARTMENTS</td>
            <td>{{ number_format($totalApartmentSqft) }}</td>
            <td>{{ number_format($totalApartmentSaleAmount) }}</td>
        </tr>

        <!-- Dynamic Data for PARKING -->
        <tr>
            <td>PARKING</td>
            <td>{{ number_format($totalparkingnumber)}}</td>
            <td>{{ number_format($totalParkingSales) }}</td>
        </tr>

        <!-- Total Sales Row -->
        <tr class="total-row">
            <td>TOTAL SALES</td>
            <td>{{ number_format($totalSqft) }}</td>
            <td>{{ number_format($totalShopSaleAmount + $totalApartmentSaleAmount + $totalParkingSales) }}</td>
        </tr>
    </tbody>
</table>


</body>
</html>