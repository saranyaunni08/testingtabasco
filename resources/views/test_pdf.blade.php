<!DOCTYPE html>
<html>
<head>
    <title>Installment Details</title>
</head>
<body>
    <h1>Installment Details</h1>
    <p><strong>Installment ID:</strong> {{ $installment->id }}</p>
    <p><strong>Sale ID:</strong> {{ $sale->id }}</p>
    <p><strong>Installment Amount:</strong> {{ $installment->installment_amount }}</p>
    <p><strong>Transaction Details:</strong> {{ $installment->transaction_details }}</p>
    <p><strong>Bank Details:</strong> {{ $installment->bank_details }}</p>
    <p><strong>Status:</strong> {{ $installment->status }}</p>
</body>
</html>
