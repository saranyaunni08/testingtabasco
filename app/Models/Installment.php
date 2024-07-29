<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'installment_date',
        'installment_amount',
        'transaction_details',
        'bank_details',
        'status',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
    protected $casts = [
        'installment_date' => 'date',
    ];
}
