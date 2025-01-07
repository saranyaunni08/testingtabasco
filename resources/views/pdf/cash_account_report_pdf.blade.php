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
    {{ $cashInstallments->min('installment_date') ? \Carbon\Carbon::parse($cashInstallments->min('installment_date'))->format('d-m-Y') : 'N/A' }}
    To
    {{ $cashInstallments->max('installment_date') ? \Carbon\Carbon::parse($cashInstallments->max('installment_date'))->format('d-m-Y') : 'N/A' }}
</p>


<table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
    <thead>
        <tr>
            <th>Date</th>
            <!-- <th>Vno</th> -->
            <th>Description</th>
            <th>Debit</th>
            <th>Credit</th>
            <th>Balance</th>
        </tr>
    </thead>
    @php
    $totalDebit = 0; // Total Debit
    $totalCredit = 0; // Total Credit
    $balance = 0; // Current Balance
    $totalBalanceSum = 0; // Sum of all balances
@endphp

@foreach ($cashInstallments as $installment)
    @php
        $debit = $installment->paid_amount ?: 0; // Debit value
        $credit = $installment->amount ?: 0; // Credit value
        $totalDebit += $debit; // Add to total debit
    @endphp

    <!-- Row for Debit (Installment Paid) -->
    <tr>
        <td>
            {{ \Carbon\Carbon::parse($installment->payment_date)->format('d-m-Y') }}
        </td>
        <!-- <td></td> -->
        <td>
            {{ $installment->installment_number }} Installment ({{ $installment->customer_name }})
        </td>
        <td>
            {{ number_format($debit, 2) }}
        </td>
        <td>
            <!-- Leave blank for credit -->
        </td>
        <td>
            @php
                $balance += $debit; // Update balance with debit
                $totalBalanceSum += $balance; // Add current balance to total balance sum
            @endphp
            {{ number_format($balance, 2) }}
        </td>
    </tr>

    <!-- Loop through unique partners for Credit (Transfer Details) -->
    @foreach ($uniquePartners->where('id', $installment->partner_id) as $partner)
        @if ($installment->installment_number == $installment->installment_number)
            <tr>
                <td>
                    {{ \Carbon\Carbon::parse($installment->payment_date)->format('d-m-Y') }}
                </td>
                <td></td>
                <td>
                    Transfer to {{ $partner->first_name }} Current Account ({{ $installment->percentage }}%)
                </td>
            
                <td>
                    {{ number_format($credit, 2) }}
                </td>
                <td>
                    @php
                        $balance -= $credit; // Subtract credit from balance
                        $totalCredit += $credit; // Add to total credit
                        $totalBalanceSum += $balance; // Add current balance to total balance sum
                    @endphp
                    {{ number_format($balance, 2) }}
                </td>
            </tr>
        @endif
    @endforeach
@endforeach

</tbody>
<tfoot>
    <tr>
        <td colspan="2" class="sub-total">Sub Total</td>
        <td>
            {{ number_format($totalDebit, 2) }}
        </td>
        <td>
            {{ number_format($totalCredit, 2) }}
        </td>
        <td class="balance">
            {{ number_format($totalBalanceSum, 2) }} <!-- Total Balance Sum -->
        </td>
    </tr>

    <tr>
        <td colspan="2" class="sub-total">Grand Total</td>
        <td>
            {{ number_format($totalDebit, 2) }}
        </td>
        <td>
            {{ number_format($totalCredit, 2) }}
        </td>
        <td class="balance">
            {{ number_format($totalBalanceSum, 2) }} <!-- Total Balance Sum -->
        </td>
    </tr>
</tfoot>
</table>

</body>
</html>
