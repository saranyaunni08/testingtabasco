<!-- resources/views/admin/installments/pdf.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paid Installments Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 50px; /* Adding margin to the body */
            line-height: 1.6;
        }

        /* Company Header */
        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            margin: 0;
            font-size: 32px;
            color: #007bff;
        }

        .header h3 {
            margin: 5px 0;
            font-size: 18px;
        }

        .header p {
            margin: 5px 0;
            font-size: 14px;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            color: #007bff;
        }

        td {
            font-size: 14px;
        }

        .table-header {
            font-weight: bold;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 14px;
            color: #555;
        }

        .footer p {
            margin: 5px 0;
        }

        .footer .small {
            font-size: 12px;
            color: #888;
        }

        /* Styling the company info and details */
        .company-info {
            margin-bottom: 10px;
        }

        .customer-info {
            margin-top: 20px;
        }

        .customer-info h4 {
            margin-bottom: 10px;
        }

    </style>
</head>
<body>

    <!-- Company Header -->
    <div class="header">
        <h1>Tabasco Hindustan Pvt Ltd</h1>
        <h3>Installments Payment Details</h3>
        <p>Address: 123, Business Street, City, State, Country</p>
        <p>Phone: +91 123 456 7890 | Email: info@tabascohindustan.com</p>
    </div>

    <!-- Customer Info -->
    <div class="customer-info">
        <h4>Customer: {{ $sale->customer_name }}</h4>
        <p>Room Number: {{ $sale->room->room_number }}</p>
        <p>Floor: {{ $sale->room->room_floor }}</p>
        <p>Room Type: {{ $sale->room->room_type }}</p>
    </div>

    <!-- Table for Installment Details -->
    <table>
        <thead>
            <tr>
                <th class="table-header">Due Date</th>
                <th class="table-header">Expected Amount </th>
                <th class="table-header">Paid Amount </th>
                <th class="table-header">Payment Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($paidInstallments as $installment)
                @foreach ($installment->payments as $payment)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($installment->installment_date)->format('d-m-Y') }}</td>
                        <td>{{ number_format($installment->installment_amount, 2) }}</td>
                        <td>{{ number_format($payment->paid_amount, 2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d-m-Y') }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>Thank you for choosing Tabasco Hindustan Pvt Ltd.</p>
        <p class="small">This is a computer-generated document and does not require a signature.</p>
    </div>

</body>
</html>
