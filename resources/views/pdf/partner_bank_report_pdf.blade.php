<!DOCTYPE html>
<html>
<head>
    <title>Cash Book Current Account PDF</title>
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
    /* Aligning h3 and p elements to center */
    h3, h4, h5, p {
        text-align: center;
    }
</style>
<body>

<!-- Header -->
<h3 class="text-center font-weight-bold">TABASCO INN</h3>
    <h4 class="text-center font-weight-bold">STATEMENT OF ACCOUNT</h4>
    <h5 class="text-center">{{ $partnerName }} Current Account</h5>


    <p style="text-align: center;">
        From
        {{ $cashInstallments->min('installment_date') ? \Carbon\Carbon::parse($cashInstallments->min('installment_date'))->format('d-m-Y') : 'N/A' }}
        To
        {{ $cashInstallments->max('installment_date') ? \Carbon\Carbon::parse($cashInstallments->max('installment_date'))->format('d-m-Y') : 'N/A' }}
    </p>


    <!-- Table -->
    <table class="table table-bordered text-center">
        <thead>
            <tr class="table-header">
                <th>Date</th>
                <!-- <th>Vno</th> -->
                <th>Description</th>
                <th>Debit</th>
                <!-- <th>Credit</th> -->
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            @php
                $balance = 0; // Initialize balance
                $totalDebit = 0; // Initialize total debit
                $totalCredit = 0; // Initialize total credit
                $totalBalance = 0; // Initialize total balance
            @endphp

            @foreach ($cashInstallments as $installment)
                        @php
                            $paidAmount = $installment->paid_amount; // Convert to float
                            $partnerAmount = $installment->amount; // Convert to float
                            $balance += $partnerAmount;

                            // Add to the running totals
                            $totalCredit += $partnerAmount;
                            
                            $totalBalance += $balance;
                        @endphp
                        <tr class="{{ $loop->iteration % 2 == 0 ? 'even-row' : 'odd-row' }}">
                            <td>{{ \Carbon\Carbon::parse($installment->payment_date)->format('d-m-Y') }}</td>
                            <td>{{ $installment->installment_number }} installment ({{ $installment->customer_name }})</td>
                            <td>{{ number_format($partnerAmount, 2) }}</td>
                            <td>{{ number_format($balance, 2) }}</td> <!-- Balance after each installment -->
        
                        </tr>
            @endforeach

        </tbody>
        <tfoot>
            <tr>
                <td colspan="2"><strong>Sub Total</strong></td>
                <td><strong>{{ number_format($totalCredit, 2) }}</strong></td>
                <td><strong>{{ number_format($totalBalance, 2) }}</strong></td>
        
            </tr>
            <tr>
                <td colspan="2"><strong>Sub Total</strong></td>
                <td><strong>{{ number_format($totalCredit, 2) }}</strong></td>
                <td><strong>{{ number_format($totalBalance, 2) }}</strong></td>
    
            </tr>

        </tfoot>

    </table>

</body>
</html>