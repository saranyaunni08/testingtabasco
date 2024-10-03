<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChequeExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id', 
        'cheque_expense_description',
        'cheque_expense_amount',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
