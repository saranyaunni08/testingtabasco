<!DOCTYPE html>
<html>

<head>
    <title>Bank Account PDF</title>
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
            border: 1px solid #000;
            /* Black border for a simple photostat style */
            padding: 8px;
            text-align: center;
        }

        th {
            font-weight: bold;
            /* Bold headers for clarity */
        }

        .balance {
            font-weight: bold;
            /* Bold the balance column */
        }

        .note {
            font-size: 12px;
            /* Smaller font for notes */
        }

        .highlight {
            background-color: transparent;
            /* No highlight color */
        }

        .sub-total {
            font-weight: bold;
            text-align: right;
        }
    </style>

</head>

<body>


    <div style="text-align: center;">
        <h2>TABASCO INN</h2>
        <h3>STATEMENT OF ACCOUNT</h3>
        <p><strong>BANK BANK ACCOUNTS (COMBINED)</strong></p>
        <p>
            From
            {{ $installments->min('installment_date') ? \Carbon\Carbon::parse($installments->min('installment_date'))->format('d-m-Y') : 'N/A' }}
            To
            {{ $installments->max('installment_date') ? \Carbon\Carbon::parse($installments->max('installment_date'))->format('d-m-Y') : 'N/A' }}
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Vno</th>
                <th>Description</th>
                <th>CHEQUE NO</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_debit = 0; // Initialize total debit
                $total_credit = 0; // Initialize total credit
                $total_balance = 0; // Initialize total balance
            @endphp

            @php
                $total_debit = 0;
                $current_balance = 0; // Initialize cumulative balance
            @endphp

            @foreach ($installments as $index => $installment)
                        @php
                            $paid_amount = floatval($installment->paid_amount);

                            // Update totals
                            $total_debit += $paid_amount;
                            $current_balance += $paid_amount; // Add current paid amount to cumulative balance
                            $total_balance += $current_balance;
                        @endphp

                        <tr @if($index % 2 == 0) class="highlight" @endif>
                            <td>{{ \Carbon\Carbon::parse($installment->payment_date)->format('d-m-Y') }}</td>
                            <td></td>
                            <td>{{ $installment->installment_number }} Installment ({{ $installment->customer_name }})</td>
                            <td>{{ $installment->cheque_number }}</td>
                            <td>{{ number_format($paid_amount, 2) }}</td>
                            <td></td>
                            <td class="balance">{{ number_format($current_balance, 2) }}</td> <!-- Show cumulative balance -->
                        </tr>
            @endforeach

        </tbody>


        <tfoot>
            <!-- Subtotal Row -->
            <tr>
                <td colspan="4" class="sub-total">Sub Total</td>
                <td>{{ number_format($total_debit, 2) }}</td>
                <td></td>
                <td class="balance">{{ number_format($total_balance, 2) }}</td>
            </tr>

            <!-- Grand Total Row -->
            <tr>
                <td colspan="4" class="sub-total">Grand Total</td>
                <td>{{ number_format($total_debit, 2) }}</td>
                <td></td>
                <td class="balance">{{ number_format($total_balance, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</body>

</html>