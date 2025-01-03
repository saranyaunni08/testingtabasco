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
        <a href="{{ route('admin.bankaccount.' . Str::slug(strtolower($bankName)) . 'account', $building->id) }}" 
           class="btn btn-outline-{{ $loop->index % 2 == 0 ? 'primary' : 'secondary' }}">
            {{ $bankName }} Account
        </a>
    @endforeach
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
                $total_debit = 0;
                $total_credit = 0;
            @endphp

            @foreach ($installments as $index => $installment)
                <tr @if($index % 2 == 0) class="highlight" @endif>
                    <td>{{ $installment->payment_date }}</td>
                    <td>-------</td>
                    <td>{{ $installment->installment_number }}{{ $installment->customer_name }}</td>
                    <td>-------</td>
                    <td>{{ number_format($installment->paid_amount, 2) }}</td>
                    <td>-------</td>
                    <td class="balance">-------</td>
                </tr>
            @endforeach
        </tbody>

        <tfoot>
            <!-- Subtotal Row -->
            <tr>
                <td colspan="4" class="sub-total">Sub Total</td>
                <td>{{ number_format($total_debit, 2) }}</td>
                <td>{{ number_format($total_credit, 2) }}</td>
                <td class="balance">{{ number_format($total_debit - $total_credit, 2) }}</td>
            </tr>

            <!-- Grand Total Row -->
            <tr>
                <td colspan="4" class="sub-total">Grand Total</td>
                <td>{{ number_format($total_debit, 2) }}</td>
                <td>{{ number_format($total_credit, 2) }}</td>
                <td class="balance">{{ number_format($total_debit - $total_credit, 2) }}</td>
            </tr>
        </tfoot>

    </table>


</div>

@endsection