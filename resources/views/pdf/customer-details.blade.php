<!DOCTYPE html>
<html>
<head>
    <title>Customer Details</title>
    <style>
        /* Add any styling you need for the PDF */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-12">
                <h4>Loan Details</h4>
                <table class="table table-sm table-bordered">
                    <tbody>
                        <tr>
                            <th>Loan No</th>
                            <td>{{ $customer->id }}</td>
                        </tr>
                        <tr>
                            <th>Disb Date</th>
                            <td>{{ $customer->created_at->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Cost of Asset</th>
                            <td>{{ $customer->total_with_discount }}</td>
                        </tr>
                        <tr>
                            <th>EMI Start Date</th>
                            <td>{{ $emi_start_date->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>EMI End Date</th>
                            <td>{{ $emi_end_date->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>EMI Amount</th>
                            <td>{{ $emi_amount }}</td>
                        </tr>
                        <tr>
                            <th>Tenure (Months)</th>
                            <td>{{ $tenure_months }}</td>
                        </tr>
                        <tr>
                            <th>Asset</th>
                            <td>{{ $room->room_type }}</td>
                        </tr>
                        <tr>
                            <th>Loan Amount</th>
                            <td>{{ $customer->remaining_balance }}</td>
                        </tr>
                        <tr>
                            <th>Current EMI OS</th>
                            <td>{{ $remainingBalanceAfterInstallments }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <h5 class="mt-4">Installment Details</h5>
        <table class="table table-sm table-bordered">
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
                            @else
                                <span class="badge bg-danger">Pending</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
