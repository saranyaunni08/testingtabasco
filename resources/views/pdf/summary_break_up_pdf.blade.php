<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Summary Breakup PDF</title>
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
        text-align: left;
    }
    th {
        font-weight: bold;
        text-align: center; /* Ensures all th content is centered */
    }
    .text-center {
        text-align: center;
    }
    h3 {
        text-align: center; /* Center-aligns the h3 heading */
        margin-top: 20px;  /* Adds some space above the heading */
    }
    </style>

</head>
<body>
    <div class="container">
        <h3>Summary</h3>

        <!-- SQ.FT SUMMARY -->
        <table>
            <thead class="table-primary">
                <tr>
                    <th colspan="3">SQ.FT SUMMARY</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Apartment Total SQ.FT</td>
                    <td colspan="2">{{ number_format($totalSqFt, 2) }}</td>
                </tr>
                <tr>
                    <td>Apartment Total SQ.FT Sold</td>
                    <td colspan="2">{{ number_format($totalSqFtSold, 2) }}</td>
                </tr>
                <tr>
                    <td>Apartment Balance SQ.FT</td>
                    <td colspan="2">{{ number_format($balanceSqFt, 2) }}</td>
                </tr>
                <tr>
                    <td>Commercial Total SQ.FT</td>
                    <td colspan="2">{{ number_format($commercialTotalSqft, 2) }}</td>
                </tr>
                <tr>
                    <td>Commercial Total SQ.FT Sold</td>
                    <td colspan="2">{{ number_format($commercialTotalSqftSold, 2) }}</td>
                </tr>
                <tr>
                    <td>Commercial Balance SQ.FT</td>
                    <td colspan="2">{{ number_format($commercialBalanceSqft, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- FINANCIALS SUMMARY -->
        <table>
            <thead class="table-success">
                <tr>
                    <th colspan="3">FINANCIALS SUMMARY</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Apartment Expected Sale</td>
                    <td colspan="2">{{ number_format($totalExpectedAmount, 2) }}</td>
                </tr>
                <tr>
                    <td>Parking Expected Sale</td>
                    <td colspan="2">{{ number_format($ParkingtotalExpectedSale, 2) }}</td>
                </tr>
                <tr>
                    <td>Commercial Expected Sale</td>
                    <td colspan="2">{{ number_format($totalcommercialExpectedAmount, 2) }}</td>
                </tr>
                <tr class="table-warning">
                    <td>Total Expected Sale (a+b+c)</td>
                    <td colspan="2">{{ number_format($totalExpectedSale, 2) }}</td>
                </tr>
                <tr>
                    <td>Apartments Sold</td>
                    <td colspan="2">{{ number_format($apartmentsSold, 2) }}</td>
                </tr>
                <tr>
                    <td>Parking Sold</td>
                    <td colspan="2">{{ number_format($parkingSold, 2) }}</td>
                </tr>
                <tr>
                    <td>Commercial Sold</td>
                    <td colspan="2">{{ number_format($commercialSold, 2) }}</td>
                </tr>
                <tr class="table-info">
                    <td>Total Sold</td>
                    <td colspan="2">{{ number_format($totalSold, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
