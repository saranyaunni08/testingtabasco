<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChequeDeduction extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'cheque_deducted_amount',
        'cheque_deduction_reason',
    ];
    
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
