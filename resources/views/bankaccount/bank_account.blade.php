@extends('layouts.default')

@section('content')
<div class="container">
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
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #bfbfbf;
        }

        .balance {
            font-weight: bold;
        }

        .note {
            color: red;
            font-size: 12px;
        }

        .highlight {
            background-color: #d6f5d6;
            /* Light green */
        }

        .sub-total {
            font-weight: bold;
            text-align: right;
        }
    </style>
   <div class="d-flex justify-content-center mb-4 gap-3">
    @foreach ($bankNames as $bankName)
        <a href="{{ route('admin.bankaccount.banknames_bank', ['building_id' => $building->id, 'bank_name' => $bankName]) }}"
            class="btn btn-outline-{{ $loop->index % 2 == 0 ? 'primary' : 'secondary' }}">
            {{ $bankName }} Account
        </a>
    @endforeach
</div>

<div style="text-align: right;">
        <a href="{{ route('admin.bank_account_report.pdf', $building->id) }}" class="btn btn-primary">
            <i class="fas fa-arrow-down"></i> Download PDF
        </a>

    </div>


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

            @foreach ($installments as $index => $installment)
                        @php
                            $balance = floatval($installment->paid_amount) - floatval($installment->partner_amounts);

                            // Update totals with typecasting
                            $total_debit += floatval($installment->paid_amount);
                            $total_credit += floatval($installment->partner_amounts);
                            $total_balance += $balance;
                        @endphp


                        <tr @if($index % 2 == 0) class="highlight" @endif>
                            <td>{{ $installment->payment_date }}</td>
                            <td></td>
                            <td>{{ $installment->installment_number }} Installment {{ $installment->customer_name }}</td>
                            <td>{{ $installment->cheque_number }}</td>
                            <td>{{ number_format(floatval($installment->paid_amount), 2) }}</td>
                            <td>{{ number_format(floatval($installment->partner_amounts), 2) }}</td>
                            <td class="balance">{{ number_format(floatval($balance), 2) }}</td>

                        </tr>
            @endforeach
        </tbody>

        <tfoot>
            <!-- Subtotal Row -->
            <tr>
                <td colspan="4" class="sub-total">Sub Total</td>
                <td>{{ number_format($total_debit, 2) }}</td>
                <td>{{ number_format($total_credit, 2) }}</td>
                <td class="balance">{{ number_format($total_balance, 2) }}</td>
            </tr>

            <!-- Grand Total Row -->
            <tr>
                <td colspan="4" class="sub-total">Grand Total</td>
                <td>{{ number_format($total_debit, 2) }}</td>
                <td>{{ number_format($total_credit, 2) }}</td>
                <td class="balance">{{ number_format($total_balance, 2) }}</td>
            </tr>
        </tfoot>
    </table>



</div>

@endsection