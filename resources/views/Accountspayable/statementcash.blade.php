@extends('layouts.default')

@section('content')
<div class="container">
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
        .header h1 {
            margin: 0;
            color: #000;
            font-size: 24px;
        }
        .header h2 {
            margin: 5px 0;
            color: #333;
            font-size: 18px;
        }
        .header p {
            margin: 0;
            color: #555;
            font-size: 14px;
        }
        .header .statement-type {
            color: green;  /* Color changed to green for cash */
            font-weight: bold;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
            font-size: 14px;
        }
        th {
            background-color: #f4b084;
            font-weight: bold;
        }
        .highlight {
            background-color: #f9f9f9;
        }
        .totals {
            font-weight: bold;
        }
        .totals td {
            padding: 10px;
            background-color: #f4f4f4;
        }
        .footer {
            margin-top: 20px;
        }
        .footer .totals td {
            border: 1px solid #000;
        }
    </style>
    <div class="header">
        <h1>TABASCO INN</h1>
        <h2>STATEMENT OF ACCOUNT</h2>
        <p><strong>Supplier:</strong> MKH</p>
        <p>From 01-09-2024 To 18-05-2026</p>
        <p class="statement-type">Statement Type: Cash</p>  <!-- Statement Type changed to Cash -->
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
                <td>15-09-2024</td>
                <td>#######</td>
                <td>Purchase</td>
                <td>Cash</td>  <!-- Payment type changed to Cash -->
                <td>700000</td>
                <td></td>
                <td>700000</td>
            </tr>
            <tr>
                <td>15-09-2024</td>
                <td>#######</td>
                <td>1st Payment</td>
                <td>Cash</td>  <!-- Payment type changed to Cash -->
                <td></td>
                <td>90000</td>
                <td>610000</td>
            </tr>
            <tr>
                <td>15-10-2024</td>
                <td>#######</td>
                <td>2nd Payment</td>
                <td>Cash</td>  <!-- Payment type changed to Cash -->
                <td></td>
                <td>90000</td>
                <td>520000</td>
            </tr>
            <tr>
                <td>15-11-2024</td>
                <td>#######</td>
                <td>3rd Payment</td>
                <td>Cash</td>  <!-- Payment type changed to Cash -->
                <td></td>
                <td>90000</td>
                <td>430000</td>
            </tr>
        </tbody>
        <tfoot>
            <tr class="totals">
                <td colspan="4">Sub Total</td>
                <td>700000</td>
                <td>270000</td>
                <td>430000</td>
            </tr>
            <tr class="totals">
                <td colspan="4">Grand Total</td>
                <td>700000</td>
                <td>270000</td>
                <td>430000</td>
            </tr>
        </tfoot>
    </table>
</div>

@endsection

