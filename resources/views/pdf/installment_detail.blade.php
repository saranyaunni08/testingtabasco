<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }
        .container {
            width: 80%;
            margin: auto;
            padding: 10px;
            border: 1px solid #ddd;
            page-break-inside: avoid;
        }
        h1, h2 {
            color: #333;
            margin: 0;
        }
        h1 {
            margin-bottom: 5px;
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        .invoice-header div {
            flex: 1;
            font-size: 12px; /* Adjust the font size here */
        }
        .invoice-header .right {
            text-align: right;
        }
        .details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .details .section {
            width: 30%;
        }
        .details h2 {
            font-size: 14px;
            margin-bottom: 5px;
            color: #555;
        }
        .details span {
            display: block;
            font-size: 12px;
            color: #555;
            margin-bottom: 5px;
        }
        .installment-table {
            margin-top: 10px;
        }
        .installment-table table {
            width: 100%;
            border-collapse: collapse;
        }
        .installment-table th, .installment-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }
        .installment-table th {
            background-color: #f2f2f2;
            color: #333;
        }
        .installment-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .installment-table tr:hover {
            background-color: #f1f1f1;
        }
        .badge {
            padding: 3px 7px;
            border-radius: 5px;
            color: #fff;
            font-size: 10px;
        }
        .badge-paid {
            background-color: #28a745;
        }
        .badge-pending {
            background-color: #dc3545;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="invoice-header">
            <div>
                <h3>Tabasco International Mall</h3>
                <p>839Q+M7X, Durga school Rd, Puthiyavalapu,<br>Kanhangad, Kerala 671531</p>
                <p>Email: info@tabasco.com</p>
                <p>Phone: +91-1234567890</p>
            </div>
            <div class="right">
                <h1>INVOICE</h1>
                <p>Invoice No: #{{ $installment->id ?? 'N/A' }}</p>
                <p>Invoice Date: {{ date('d/m/Y') }}</p>
                <p>Due Date: {{ date('d/m/Y', strtotime($emi_end_date)) }}</p>
            </div>
        </div>
        <div class="details">
            <div class="section">
                <h2>BILL TO</h2>
                <span><strong>Name:</strong> {{ $customer_name }}</span>
                <span><strong>Email:</strong> {{ $customer_email }}</span>
                <span><strong>Contact:</strong> {{ $customer_contact }}</span>
            </div>
            <div class="section">
                <span><strong>Loan No:</strong> {{ $installment->id ?? 'N/A' }}</span>
                <span><strong>Cost of Asset:</strong> {{ number_format($sale->total_with_discount ?? 0, 2) }}</span>
                <span><strong>Loan Amount:</strong> {{ number_format($sale->remaining_balance ?? 0, 2) }}</span>
            </div>
            <div class="section">
                <h2>EMI DETAILS</h2>
                <span><strong>EMI Start Date:</strong> {{ date('d/m/Y', strtotime($emi_start_date)) }}</span>
                <span><strong>EMI End Date:</strong> {{ date('d/m/Y', strtotime($emi_end_date)) }}</span>
                <span><strong>EMI Amount:</strong> {{ number_format($emi_amount ?? 0, 2) }}</span>
                <span><strong>Tenure (Months):</strong> {{ $tenure_months }}</span>
                <span><strong>Asset Type:</strong> {{ $room->room_type ?? 'N/A' }}</span>
                <span><strong>Current EMI OS:</strong> {{ number_format($remainingBalanceAfterInstallments ?? 0, 2) }}</span>
            </div>
        </div>
        <div class="installment-table">
            <h2>Installment Details</h2>
            <table>
                <thead>
                    <tr>
                        <th>SL No</th>
                        <th>ID</th>
                        <th>Installment Date</th>
                        <th>Amount</th>
                        <th>Transaction Details</th>
                        <th>Bank Details</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>{{ $installment->id }}</td>
                        <td>{{ date('d/m/Y', strtotime($installment->installment_date)) }}</td>
                        <td>{{ number_format($installment->installment_amount, 2) }}</td>
                        <td>{{ $installment->transaction_details }}</td>
                        <td>{{ $installment->bank_details }}</td>
                        <td><span class="badge {{ $installment->status == 'paid' ? 'badge-paid' : 'badge-pending' }}">{{ ucfirst($installment->status) }}</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="footer">
            <p>Thank you for your business!</p>
            <p>Terms & Instructions: Add payment instructions here, e.g: bank, paypal...</p>
            <p>Add terms here, e.g: warranty, returns policy...</p>
        </div>
    </div>
</body>
</html>
