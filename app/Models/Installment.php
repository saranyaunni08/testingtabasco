<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    use HasFactory;

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
