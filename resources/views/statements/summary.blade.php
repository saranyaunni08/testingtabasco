@extends('layouts.default')

@section('content')
<div class="container mt-5" style="background-color: #f9f9f9; border-radius: 10px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); padding: 20px;">
    <div class="text-center mb-4">
        <h2 class="font-weight-bold" style="color: #333;">{{ ucfirst(strtolower($sale->customer_name)) }} - Summary</h2>
    </div>

    <table class="table table-striped table-bordered" style="background-color: white;">
        <thead class="thead-dark">
            <tr>
                <th style="width: 70%;">Description</th>
                <th style="width: 30%;" class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Sale of Shop</strong></td>
                <td class="text-right">{{ number_format($saleAmount, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Land Exchange</strong></td>
                <td class="text-right">{{ number_format(0, 2) }}</td>
            </tr>
            <tr>
                <td><strong>By Cash</strong></td>
                <td class="text-right">{{ number_format($cashReceived, 2) }}</td>
            </tr>
            <tr>
                <td><strong>By Cheque</strong></td>
                <td class="text-right">{{ number_format($chequeReceived, 2) }}</td>
            </tr>
            <tr>
                <td><strong>GST 18%</strong></td>
                <td class="text-right">{{ number_format($gst, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Total Receivable</strong></td>
                <td class="text-right">{{ number_format($totalReceivable, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Cash Received</strong></td>
                <td class="text-right">{{ number_format($cashReceived, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Cheque Received</strong></td>
                <td class="text-right">{{ number_format($chequeReceived, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Total Received</strong></td>
                <td class="text-right">{{ number_format($totalReceived, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Balance Receivable</strong></td>
                <td class="text-right">{{ number_format($balanceReceivable, 2) }}</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
