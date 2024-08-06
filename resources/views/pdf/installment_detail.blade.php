<!DOCTYPE html>
<html>
<head>
    <title>Installment Detail</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            color: #333;
            text-align: center;
        }
        .details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .details .left, .details .right {
            flex: 1;
            margin: 0 10px;
        }
        .details .left span, .details .right span {
            display: block;
            font-size: 16px;
            color: #555;
            margin-bottom: 5px;
        }
        .installment-table table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .installment-table th, .installment-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .installment-table th {
            background-color: #f2f2f2;
        }
        .installment-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .installment-table tr:hover {
            background-color: #f1f1f1;
        }
        .project-name {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="project-name">
            TABASCO
        </div>
        <h1>Installment Details</h1>

        <div class="details">
            <div class="left">
                <h2>Customer Details</h2>
                <span>Customer Name: {{ $customer_name }}</span>
                <span>Customer Email: {{ $customer_email }}</span>
                <span>Customer Contact: {{ $customer_contact }}</span>
                <span>Loan No: {{ $installment->id ?? 'N/A' }}</span>
                <span>Cost of Asset: {{ number_format($sale->total_with_discount ?? 0, 2) }}</span>
                <span>Loan Amount: {{ number_format($sale->remaining_balance ?? 0, 2) }}</span>
            </div>
            <div class="right">
                <span>EMI Start Date: {{ $emi_start_date }}</span>
                <span>EMI End Date: {{ $emi_end_date }}</span>
                <span>EMI Amount: {{ number_format($emi_amount ?? 0, 2) }}</span>
                <span>Tenure (Months): {{ $tenure_months }}</span>
                <span>Asset: {{ $room->room_type ?? 'N/A' }}</span>
                <span>Current EMI OS: {{ number_format($remainingBalanceAfterInstallments ?? 0, 2) }}</span>
            </div>
        </div>

        <div class="installment-table">
            <h2>Installment Details</h2>
            <table>
                <tr>
                    <th>SL No</th>
                    <th>ID</th>
                    <th>Installment Date</th>
                    <th>Amount</th>
                    <th>Transaction Details</th>
                    <th>Bank Details</th>
                    <th>Status</th>
                </tr>
                <tr>
                    <td>1</td>
                    <td>{{ $installment->id }}</td>
                    <td>{{ $installment->installment_date }}</td>
                    <td>{{ number_format($installment->installment_amount, 2) }}</td>
                    <td>{{ $installment->transaction_details }}</td>
                    <td>{{ $installment->bank_details }}</td>
                    <td>{{ $installment->status }}</td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
