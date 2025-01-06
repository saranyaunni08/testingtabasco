<!DOCTYPE html>
<html>
<head>
    <title>Cash Book PDF</title>
</head>
<style>
    body {
        font-family: Arial, sans-serif;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        border: 1px solid #000; /* Black border for a simple photostat style */
        padding: 8px;
        text-align: center;
    }

    th {
        font-weight: bold; /* Bold headers for clarity */
    }

    .balance {
        font-weight: bold; /* Bold the balance column */
    }

    .note {
        font-size: 12px; /* Smaller font for notes */
    }

    .highlight {
        background-color: transparent; /* No highlight color */
    }

    .sub-total {
        font-weight: bold;
        text-align: right;
    }
</style>
<body>
<h1 style="text-align: center;">TABASCO INN</h1>
<h3 style="text-align: center;">STATEMENT OF ACCOUNT</h3>
<h4 style="text-align: center;">CASH BOOK</h4>
<p style="text-align: center;">
    From
    {{ $installments->min('installment_date') ? \Carbon\Carbon::parse($installments->min('installment_date'))->format('d-m-Y') : 'N/A' }}
    To
    {{ $installments->max('installment_date') ? \Carbon\Carbon::parse($installments->max('installment_date'))->format('d-m-Y') : 'N/A' }}
</p>


<table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
    <thead>
        <tr>
            <th>Date</th>
            <th>Vno</th>
            <th>Description</th>
            <th>Debit</th>
            <th>Credit</th>
            <th>Balance</th>
        </tr>
    </thead>
    <tbody>
        @php
            $balance = 0; // Initialize balance
            $totalDebit = 0; // Initialize total for paid_amount
            $totalCredit = 0; // Initialize total for partner_amounts
        @endphp

        @foreach ($installments as $installment)
            @php
                $debit = (float) $installment->paid_amount ?: 0;
                $credit = (float) $installment->partner_amounts ?: 0;
                $net_balance = $debit - $credit;
                $balance += $net_balance;

                // Add to running totals
                $totalDebit += $debit;
                $totalCredit += $credit;
            @endphp

            <tr>
                <td>{{ \Carbon\Carbon::parse($installment->payment_date)->format('d-m-Y') }}</td>
                <td></td>

                <td>{{ $installment->installment_number }} Installment ({{ $installment->customer_name }})</td>

                <td>{{ number_format($debit, 2) }}</td>
                <td>{{ number_format($credit, 2) }}</td>
                <td>{{ number_format($net_balance, 2) }}</td>
            </tr>

            @foreach ($installments as $transfer)
                @if ($installment->installment_number == $transfer->installment_number)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($transfer->payment_date)->format('d-m-Y') }}</td>
                        <td></td>
                        <td>Transfer to {{ $transfer->first_name }} Current Account ({{ $transfer->percentage }}%)</td>
                        <td>{{ number_format($debit, 2) }}</td>
                        <td>{{ number_format($credit, 2) }}</td>
                        <td>{{ number_format($net_balance, 2) }}</td>
                    </tr>
                @endif
            @endforeach
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3">Sub Total</td>
            <td>{{ number_format($totalDebit, 2) }}</td>
            <td>{{ number_format($totalCredit, 2) }}</td>
            <td>{{ number_format($balance, 2) }}</td>
        </tr>

        <tr>
            <td colspan="3">Grand Total</td>
            <td>{{ number_format($totalDebit, 2) }}</td>
            <td>{{ number_format($totalCredit, 2) }}</td>
            <td>{{ number_format($balance, 2) }}</td>
        </tr>
    </tfoot>
</table>

</body>
</html>
