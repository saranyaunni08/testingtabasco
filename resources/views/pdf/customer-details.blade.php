<!DOCTYPE html>
<html>
<head>
    <title>Customer Details Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
            color: #555;
        }
        .details {
            margin-bottom: 20px;
        }
        .details span {
            display: inline-block;
            width: 45%;
            margin-bottom: 10px;
        }
        .badge {
            padding: 5px 10px;
            color: #fff;
            border-radius: 5px;
        }
        .badge.bg-success {
            background-color: #28a745;
        }
        .badge.bg-danger {
            background-color: #dc3545;
        }
        .installments-table {
            width: 100%;
            border-collapse: collapse;
        }
        .installments-table th, .installments-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .installments-table th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <h2>Customer Loan Details</h2>
        <div class="details">
            <span>Customer Name: {{ $customer->customer_name }}</span>
            <span>Customer Email: {{ $customer->customer_email }}</span><br>
            <span>Customer Contact: {{ $customer->customer_contact }}</span><br>
            <span>Loan No: {{ $customer->id }}</span>
            <span>EMI Start Date: {{ $emi_start_date->format('d/m/Y') }}</span><br>
            <span>Disb Date: {{ $customer->created_at->format('d/m/Y') }}</span>
            <span>EMI End Date: {{ $emi_end_date->format('d/m/Y') }}</span><br>
            <span>Cost of Asset: {{ $customer->total_with_discount }}</span>
            <span>EMI Amount: {{ $emi_amount }}</span><br>
            <span>Tenure (Months): {{ $tenure_months }}</span>
            <span>Asset: {{ $room->room_type }}</span><br>
            <span>Loan Amount: {{ $customer->remaining_balance }}</span>
            <span>Current EMI OS: {{ $remainingBalanceAfterInstallments }}</span>
        </div>

        <h4 class="mt-4">Installment Details</h4>
        <table class="installments-table">
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
                @php
                    $remaining_tenure = $tenure_months;
                @endphp
                @foreach($installments as $installment)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $installment->id }}</td>
                        <td>{{ $installment->installment_date->format('d/m/Y') }}</td>
                        <td>{{ $installment->installment_amount }}</td>
                        <td>{{ $installment->transaction_details }}</td>
                        <td>{{ $installment->bank_details }}</td>
                        <td>
                            @if($installment->status === 'paid')
                                <span class="badge bg-success">Paid</span>
                                @php
                                    $remaining_tenure--;
                                @endphp
                            @else
                                <span class="badge bg-danger">Pending</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h4 class="mt-4">Updated Tenure</h4>
        <table class="installments-table">
            <tr>
                <th>Remaining Tenure (Months)</th>
                <td>{{ $remaining_tenure }}</td>
            </tr>
        </table>
    </div>
</body>
</html>
