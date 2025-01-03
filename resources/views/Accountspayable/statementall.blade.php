@extends('layouts.default')

@section('content')
<div class="d-flex justify-content-center mb-4 gap-3">
    <a href="{{ route('admin.Accountspayable.statementcash', $building->id) }}" class="btn btn-outline-primary">Statement Cash Only</a>
    <a href="{{ route('admin.Accountspayable.statementcheque',$building->id)}}" class="btn btn-outline-secondary">Statement Cheque Only</a>

  
</div>
<div class="container">
    <!-- Download PDF Button -->
    <div class="d-flex justify-content-center mb-4 gap-3">
    <a href="{{ route('admin.Accountspayable.downloadStatement', $building->id) }}?download=true" class="btn btn-outline-primary">Download Statement as PDF</a>

</div>



    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .container {
            width: 90%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            color: #333;
        }
        .header h3 {
            margin: 5px 0;
            color: #555;
        }
        .header p {
            margin: 0;
            color: #666;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
            font-size: 14px;
        }
        th {
            background-color: #f4b084;
            color: #000;
            font-weight: bold;
        }
        .highlight {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 20px;
        }
        .footer .totals {
            text-align: right;
            font-weight: bold;
        }
        .totals td {
            padding: 8px;
            border: 1px solid #ddd;
            background-color: #f4f4f4;
        }
    </style>

    <div class="header">
        <h2>TABASCO INN</h2>
        <h3>STATEMENT OF ACCOUNT</h3>
        <p><strong>Supplier:</strong> MKH</p>
        <p>From 01-09-2024 To 18-05-2026</p>
        <p style="color: red;"><strong>Statement Type: All</strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>V. No</th>
                <th>Description</th>
                <th>Payment Type</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>#######</td>
                <td>#######</td>
                <td>Purchase</td>
                <td>Cash</td>
                <td>300000</td>
                <td></td>
                <td>300000</td>
            </tr>
            <tr class="highlight">
                <td>#######</td>
                <td>#######</td>
                <td>Purchase</td>
                <td>Cheque</td>
                <td>700000</td>
                <td></td>
                <td>1000000</td>
            </tr>
            <tr>
                <td>#######</td>
                <td>#######</td>
                <td>1st Payment</td>
                <td>Cash</td>
                <td>10000</td>
                <td></td>
                <td>990000</td>
            </tr>
            <tr class="highlight">
                <td>#######</td>
                <td>#######</td>
                <td>1st Payment</td>
                <td>Cheque</td>
                <td></td>
                <td>90000</td>
                <td>900000</td>
            </tr>
            <!-- Add more rows as needed -->
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="totals">Sub Total</td>
                <td>1000000</td>
                <td>300000</td>
                <td>700000</td>
            </tr>
            <tr>
                <td colspan="4" class="totals">Grand Total</td>
                <td>1000000</td>
                <td>300000</td>
                <td>700000</td>
            </tr>
        </tfoot>
    </table>
</div>
@endsection
