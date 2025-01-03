@extends('layouts.default')

@section('content')
<div class="container">

    <div class="d-flex justify-content-center mb-4 gap-3">
        <a href="{{ route('admin.cashbook.BasheerCurrentAccount', $building->id) }}"
            class="btn btn-outline-primary">Basheer Current Account</a>
        <a href="{{ route('admin.cashbook.PavoorCurrentAccount', $building->id)}}"
            class="btn btn-outline-secondary">Pavoor Current Account</a>


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
                <th style="border: 1px solid #ccc; padding: 10px; background-color: #444; color: white;">Description
                </th>
                <th style="border: 1px solid #ccc; padding: 10px; background-color: #444; color: white;">Debit</th>
                <th style="border: 1px solid #ccc; padding: 10px; background-color: #444; color: white;">Credit</th>
                <th style="border: 1px solid #ccc; padding: 10px; background-color: #444; color: white;">Balance</th>
            </tr>
        </thead>
        <tbody>
            @php
                $balance = 0; // Initialize balance
            @endphp

            @foreach ($installments as $installment)
                        <tr>
                            <td style="border: 1px solid #ccc; padding: 10px; text-align: center;">
                                {{ \Carbon\Carbon::parse($installment->installment_date)->format('d-m-Y') }}
                            </td>
                            <td style="border: 1px solid #ccc; padding: 10px; text-align: center;">
                                {{ $installment->installment_number }} Installment ({{ $installment->customer_name }})
                            </td>

                            <td style="border: 1px solid #ccc; padding: 10px; text-align: center;">
                                {{ number_format($installment->paid_amount, 2) }}
                            </td>
                            <td style="border: 1px solid #ccc; padding: 10px;"></td>
                            <td style="border: 1px solid #ccc; padding: 10px; text-align: center;">
                                @php
                                    $balance += $installment->paid_amount;
                                @endphp
                                {{ number_format($balance, 2) }}
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
                                    <td style="border: 1px solid #ccc; padding: 10px;"></td>
                                    <td style="border: 1px solid #ccc; padding: 10px; text-align: center;">
                                        {{ number_format(($installment->paid_amount * ($transfer->percentage / 100)), 2) }}
                                    </td>
                                    <td style="border: 1px solid #ccc; padding: 10px; text-align: center;">
                                        @php
                                            $balance -= ($installment->paid_amount * ($transfer->percentage / 100));
                                        @endphp
                                        {{ number_format($balance, 2) }}
                                    </td>
                                </tr>
                            @endif
                        @endforeach
            @endforeach
        </tbody>
    </table>


</div>
@endsection