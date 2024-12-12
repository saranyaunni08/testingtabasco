<?php

namespace App\Models; // Ensure this is correct

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashInstallment extends Model
{
    use HasFactory; // Add this if you are using factories

    protected $fillable = [
        'sale_id',
        'installment_frequency',
        'installment_date',
        'installment_number',
        'installment_amount',
        'status',
    ];
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
