<!DOCTYPE html>
<html>
<head>
    <title>Balance Summary PDF</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        font-size: 12px;
        margin: 0;
        padding: 0;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    th, td {
        border: 1px solid #000;
        padding: 5px;
        text-align: center;
    }
    th {
        font-weight: bold;
        background-color: #fff; /* No background color */
        color: #000; /* Black text */
    }
    .table-total {
        font-weight: bold;
        background-color: #fff; /* No background color */
    }
    .text-center {
        text-align: center;
    }
</style>

</head>
<body>
    <h2 style="text-align: center;">Balance SqFt and Financial Summary</h2>


    <!-- Room Data Table -->
    <table>
        <thead class="table-header">
            <tr>
                <th>TYPE</th>
                <th>TOTAL SQ FT</th>
                <th>SALES SQFT</th>
                <th>SALE AMOUNT</th>
                <th>BALANCE SQFT</th>
                <th>EXPECTED BALANCE AMOUNT</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalSqFtAll = 0;
                $salesSqFtAll = 0;
                $saleAmountAll = 0;
                $balanceSqFtAll = 0;
                $expectedBalanceAmountAll = 0;
            @endphp

            @foreach ($roomTypeData as $room)
                <tr>
                    <td>{{ strtoupper($room['type']) }}</td>
                    <td>{{ number_format($room['totalSqFt'], 2) }}</td>
                    <td>{{ number_format($room['salesSqFt'], 2) }}</td>
                    <td>{{ number_format($room['saleAmount'], 2) }}</td>
                    <td>{{ number_format($room['balanceSqFt'], 2) }}</td>
                    <td>{{ number_format($room['expectedBalanceAmount'], 2) }}</td>
                </tr>

                @php
                    $totalSqFtAll += $room['totalSqFt'];
                    $salesSqFtAll += $room['salesSqFt'];
                    $saleAmountAll += $room['saleAmount'];
                    $balanceSqFtAll += $room['balanceSqFt'];
                    $expectedBalanceAmountAll += $room['expectedBalanceAmount'];
                @endphp
            @endforeach

            <tr class="table-total">
                <td>TOTAL</td>
                <td>{{ number_format($totalSqFtAll, 2) }}</td>
                <td>{{ number_format($salesSqFtAll, 2) }}</td>
                <td>{{ number_format($saleAmountAll, 2) }}</td>
                <td>{{ number_format($balanceSqFtAll, 2) }}</td>
                <td>{{ number_format($expectedBalanceAmountAll, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Parking Data Table -->
    <table>
        <thead class="table-header">
            <tr>
                <th>TYPE</th>
                <th>TOTAL SQ FT</th>
                <th>SALES SQFT</th>
                <th>SALE AMOUNT</th>
                <th>BALANCE SQFT</th>
                <th>EXPECTED BALANCE AMOUNT</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ strtoupper($parkingData['type']) }}</td>
                <td>{{ number_format($parkingData['totalSqFt'], 2) }}</td>
                <td>{{ number_format($parkingData['salesSqFt'], 2) }}</td>
                <td>{{ number_format($parkingData['saleAmount'], 2) }}</td>
                <td>{{ number_format($parkingData['balanceSqFt'], 2) }}</td>
                <td>{{ number_format($parkingData['expectedBalanceAmount'], 2) }}</td>
            </tr>
            @php
                    $totalParkingSqFtAll += $parkingData['totalSqFt'];
                    $salesParkingSqFtAll += $parkingData['salesSqFt'];
                    $saleParkingAmountAll += $parkingData['saleAmount'];
                    $balanceParkingSqFtAll += $parkingData['balanceSqFt'];
                    $expectedParkingBalanceAmountAll += $parkingData['expectedBalanceAmount'];
                @endphp
                @endforeach

            <tr class="table-total">
                <td>TOTAL</td>
                <td>{{ number_format($totalSqFtAll, 2) }}</td>
                <td>{{ number_format($salesSqFtAll, 2) }}</td>
                <td>{{ number_format($saleAmountAll, 2) }}</td>
                <td>{{ number_format($balanceSqFtAll, 2) }}</td>
                <td>{{ number_format($expectedBalanceAmountAll, 2) }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
