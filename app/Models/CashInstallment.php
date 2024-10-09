<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashInstallment extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'cash_loan_type',
        'other_loan_description_cash',
        'cash_installment_frequency',
        'cash_installment_start_date',
        'cash_no_of_installments',
        'cash_installment_amount',
        'status',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
