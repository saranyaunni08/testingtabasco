<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cash Statement</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px 12px; border: 1px solid #ddd; }
        th { background-color: #f5f5f5; text-align: left; }
        .text-end { text-align: right; }
    </style>
</head>
<body>
    <center>
    <h2 style="text-align: center;">TABASCO INN</h2>
    <h4>Client Name: {{ $sale->customer_name }}</h4>
    <h4><u>Statement of Account</u></h4>
    <p>From <strong>{{ \Carbon\Carbon::parse($firstInstallmentDate)->format('d/m/Y') }}</strong> 
       To <strong>{{ \Carbon\Carbon::parse($lastInstallmentDate)->format('d/m/Y') }}</strong></p>
    <p class="text-danger">Statement Type: Cash</p>
</center>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>V.No</th>
                <th>Description</th>
                <th>Payment Type</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            @php $balance = 0; @endphp
            @foreach($cashInstallments as $installment)
                @php
                    $balance += $installment->debit - $installment->credit;
                @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($installment->installment_date)->format('d/m/Y') }}</td>
                    <td>{{ $installment->voucher_no }}</td>
                    <td>{{ $installment->description }}</td>
                    <td>Cash</td>
                    <td>{{ number_format($installment->debit, 2) }}</td>
                    <td>
                        @if ($installment->status === 'paid')
                            {{ number_format($installment->installment_amount, 2) }}
                        @else
                            {{ number_format($installment->credit, 2) }}
                        @endif
                    </td>
                    <td>{{ number_format($balance, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-end">Sub Total</th>
                <th>{{ number_format($totalDebit, 2) }}</th>
                <th>{{ number_format($totalCredit, 2) }}</th>
                <th>{{ number_format($balance, 2) }}</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
