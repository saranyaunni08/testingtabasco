<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashInstallmentPayment extends Model
{
    use HasFactory;
// the below code shows the table name 
    protected $table = 'cashinstallment_payments';

    protected $fillable = ['cash_installment_id', 'sale_id', 'paid_amount', 'payment_date'];

    // Define relationships (if needed)
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
    public function cashInstallment()
    {
        return $this->belongsTo(CashInstallment::class);
    }
}
