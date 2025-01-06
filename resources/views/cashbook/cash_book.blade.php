@extends('layouts.default')

@section('content')
<div class="container">

<div class="d-flex justify-content-center mb-4 gap-3">
    @foreach ($partnerNames as $partner)
        <a href="{{ route('admin.cashbook.partner_bank', ['building_id' => $building->id, 'partner_name' => $partner->first_name]) }}"
            class="btn btn-outline-{{ $loop->index % 2 == 0 ? 'primary' : 'secondary' }}">
            {{ $partner->first_name }} Current Account
        </a>
    @endforeach
</div>
<div style="text-align: right;">
        <a href="{{ route('admin.cash_account_report.pdf', $building->id) }}" class="btn btn-primary">
            <i class="fas fa-arrow-down"></i> Download PDF
        </a>

    </div>

    <h1 style="text-align: center; color: #b45f04;">TABASCO INN</h1>
    <h3 style="text-align: center; color: #b45f04;">STATEMENT OF ACCOUNT</h3>
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
            <th style="border: 1px solid #ccc; padding: 10px; background-color: #444; color: white;">Date</th>
            <th style="border: 1px solid #ccc; padding: 10px; background-color: #444; color: white;">Vno</th>
            <th style="border: 1px solid #ccc; padding: 10px; background-color: #444; color: white;">Description</th>
            <th style="border: 1px solid #ccc; padding: 10px; background-color: #444; color: white;">Debit</th>
            <th style="border: 1px solid #ccc; padding: 10px; background-color: #444; color: white;">Credit</th>
            <th style="border: 1px solid #ccc; padding: 10px; background-color: #444; color: white;">Balance</th>
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
                <td style="border: 1px solid #ccc; padding: 10px; text-align: center;">
                    {{ \Carbon\Carbon::parse($installment->payment_date)->format('d-m-Y') }}
                </td>
                <td style="border: 1px solid #ccc; padding: 10px;"></td>

                <td style="border: 1px solid #ccc; padding: 10px;">
                    {{ $installment->installment_number }} Installment ({{ $installment->customer_name }})
                </td>

                <td style="border: 1px solid #ccc; padding: 10px; text-align: center;">
                    {{ number_format($debit, 2) }}
                </td>
                <td style="border: 1px solid #ccc; padding: 10px; text-align: center;">
                    {{ number_format($credit, 2) }}
                </td>
                <td style="border: 1px solid #ccc; padding: 10px; text-align: center;">
                    {{ number_format($net_balance, 2) }}
                </td>
            </tr>

            @foreach ($installments as $transfer)
                @if ($installment->installment_number == $transfer->installment_number)
                    <tr style="background-color: #d9f2d9;">
                        <td style="border: 1px solid #ccc; padding: 10px; text-align: center;">
                            {{ \Carbon\Carbon::parse($transfer->payment_date)->format('d-m-Y') }}
                        </td>
                        <td style="border: 1px solid #ccc; padding: 10px; text-align: center;"></td>
                        <td style="border: 1px solid #ccc; padding: 10px;">
                            Transfer to {{ $transfer->first_name }} Current Account ({{ $transfer->percentage }}%)
                        </td>
                        <td style="border: 1px solid #ccc; padding: 10px; text-align: center;">
                            {{ number_format($debit, 2) }}
                        </td>
                        <td style="border: 1px solid #ccc; padding: 10px; text-align: center;">
                            {{ number_format($credit, 2) }}
                        </td>
                        <td style="border: 1px solid #ccc; padding: 10px; text-align: center;">
                            {{ number_format($net_balance, 2) }}
                        </td>
                    </tr>
                @endif
            @endforeach
        @endforeach
    </tbody>
    <tfoot>
        <tr style="background-color: #f2f2f2; font-weight: bold; color: #333; text-align:center;">
            <td colspan="3" class="sub-total">Sub Total</td>
            <td style="border: 1px solid #ccc; padding: 10px; text-align: center; background-color: #e0e0e0;">
                {{ number_format($totalDebit, 2) }}
            </td>
            <td style="border: 1px solid #ccc; padding: 10px; text-align: center; background-color: #e0e0e0;">
                {{ number_format($totalCredit, 2) }}
            </td>
            <td class="balance" style="border: 1px solid #ccc; padding: 10px; text-align: center; background-color: #e0e0e0;">
                {{ number_format($balance, 2) }}
            </td>
        </tr>

        <tr style="background-color: #f2f2f2; font-weight: bold; color: #333; text-align:center;">
            <td colspan="3" class="sub-total">Grand Total</td>
            <td style="border: 1px solid #ccc; padding: 10px; text-align: center; background-color: #e0e0e0;">
                {{ number_format($totalDebit, 2) }}
            </td>
            <td style="border: 1px solid #ccc; padding: 10px; text-align: center; background-color: #e0e0e0;">
                {{ number_format($totalCredit, 2) }}
            </td>
            <td class="balance" style="border: 1px solid #ccc; padding: 10px; text-align: center; background-color: #e0e0e0;">
                {{ number_format($balance, 2) }}
            </td>
        </tr>
    </tfoot>
</table>


</div>
@endsection