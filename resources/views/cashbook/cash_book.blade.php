@extends('layouts.default')

@section('content')
<div class="container">

    <div class="d-flex justify-content-center mb-4 gap-3">
        @foreach ($uniquePartners as $partner)
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
        {{ $cashInstallments->min('installment_date') ? \Carbon\Carbon::parse($cashInstallments->min('installment_date'))->format('d-m-Y') : 'N/A' }}
        To
        {{ $cashInstallments->max('installment_date') ? \Carbon\Carbon::parse($cashInstallments->max('installment_date'))->format('d-m-Y') : 'N/A' }}
    </p>

    <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
        <thead>
            <tr>
                <th style="border: 1px solid #ccc; padding: 10px; background-color: #444; color: white;">Date</th>
                <!-- <th style="border: 1px solid #ccc; padding: 10px; background-color: #444; color: white;">Vno</th> -->
                <th style="border: 1px solid #ccc; padding: 10px; background-color: #444; color: white;">Description
                </th>
                <th style="border: 1px solid #ccc; padding: 10px; background-color: #444; color: white;">Debit</th>
                <th style="border: 1px solid #ccc; padding: 10px; background-color: #444; color: white;">Credit</th>
                <th style="border: 1px solid #ccc; padding: 10px; background-color: #444; color: white;">Balance</th>
            </tr>
        </thead>
        <tbody>
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
        <td style="border: 1px solid #ccc; padding: 10px; text-align: center;">
            {{ \Carbon\Carbon::parse($installment->payment_date)->format('d-m-Y') }}
        </td>
        <!-- <td></td> -->
        <td style="border: 1px solid #ccc; padding: 10px;">
            {{ $installment->installment_number }} Installment ({{ $installment->customer_name }})
        </td>
        <td style="border: 1px solid #ccc; padding: 10px; text-align: center;">
            {{ number_format($debit, 2) }}
        </td>
        <td style="border: 1px solid #ccc; padding: 10px; text-align: center;">
            <!-- Leave blank for credit -->
        </td>
        <td style="border: 1px solid #ccc; padding: 10px; text-align: center;">
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
            <tr style="background-color: #d9f2d9;">
                <td style="border: 1px solid #ccc; padding: 10px; text-align: center;">
                    {{ \Carbon\Carbon::parse($installment->payment_date)->format('d-m-Y') }}
                </td>
                <!-- <td></td> -->
                <td style="border: 1px solid #ccc; padding: 10px;">
                    Transfer to {{ $partner->first_name }} Current Account ({{ $installment->percentage }}%)
                </td>
                <td style="border: 1px solid #ccc; padding: 10px; text-align: center;">
                    <!-- Leave blank for debit -->
                </td>
                <td style="border: 1px solid #ccc; padding: 10px; text-align: center;">
                    {{ number_format($credit, 2) }}
                </td>
                <td style="border: 1px solid #ccc; padding: 10px; text-align: center;">
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
    <tr style="background-color: #f2f2f2; font-weight: bold; color: #333; text-align:center;">
        <td colspan="2" class="sub-total">Sub Total</td>
        <td style="border: 1px solid #ccc; padding: 10px; text-align: center; background-color: #e0e0e0;">
            {{ number_format($totalDebit, 2) }}
        </td>
        <td style="border: 1px solid #ccc; padding: 10px; text-align: center; background-color: #e0e0e0;">
            {{ number_format($totalCredit, 2) }}
        </td>
        <td class="balance"
            style="border: 1px solid #ccc; padding: 10px; text-align: center; background-color: #e0e0e0;">
            {{ number_format($totalBalanceSum, 2) }} <!-- Total Balance Sum -->
        </td>
    </tr>

    <tr style="background-color: #f2f2f2; font-weight: bold; color: #333; text-align:center;">
        <td colspan="2" class="sub-total">Grand Total</td>
        <td style="border: 1px solid #ccc; padding: 10px; text-align: center; background-color: #e0e0e0;">
            {{ number_format($totalDebit, 2) }}
        </td>
        <td style="border: 1px solid #ccc; padding: 10px; text-align: center; background-color: #e0e0e0;">
            {{ number_format($totalCredit, 2) }}
        </td>
        <td class="balance"
            style="border: 1px solid #ccc; padding: 10px; text-align: center; background-color: #e0e0e0;">
            {{ number_format($totalBalanceSum, 2) }} <!-- Total Balance Sum -->
        </td>
    </tr>
</tfoot>

    </table>



</div>
@endsection