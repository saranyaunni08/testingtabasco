<!DOCTYPE html>
<html>
<head>
    <title>Installment Invoice - {{ $sale->customer_name }}</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px;
            padding: 0;
            background-color: #f4f4f4;
        }
        .invoice-container {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: auto;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #333;
            margin: 0;
        }
        .header p {
            color: #555;
            margin: 5px 0 0;
        }
        .company-logo {
            height: 80px;
            margin-bottom: 20px;
        }
        .details {
            margin-bottom: 20px;
        }
        .details p {
            margin: 0;
            line-height: 1.6;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #17a2b8;
            color: white;
        }
        .total {
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            {{-- <img src="https://via.placeholder.com/150x80?text=Company+Logo" alt="Tabasco Logo" class="company-logo"> --}}
            <h1>Tabasco Hindustan Developers Pvt Ltd</h1>
            <p>123 Developer Street, City, State - ZIP Code</p>
            <p>Email: contact@tabascohindustan.com | Phone: +91-1234567890</p>
        </div>

        <h2>Installment Invoice for {{ $sale->customer_name }}</h2>
        <div class="details">
            <p><strong>Room Number:</strong> {{ $sale->room->room_number }}</p>
            <p><strong>Floor:</strong> {{ $sale->room->room_floor }}</p>
            <p><strong>Type:</strong> {{ $sale->room->room_type }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Installment Date</th>
                    <th>Installment Amount </th>
                    <th>Paid Amount </th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sale->installments as $installment)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($installment->installment_date)->format('d-m-Y') }}</td>
                        <td>{{ number_format($installment->installment_amount, 2) }}</td>
                        <td>{{ number_format($installment->total_paid ?? 0, 2) }}</td>
                        <td>{{ ucfirst($installment->status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <p>Thank you for choosing Tabasco Hindustan Developers Pvt Ltd. We appreciate your business!</p>
            <p>&copy; {{ date('Y') }} Tabasco Hindustan Developers Pvt Ltd. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
