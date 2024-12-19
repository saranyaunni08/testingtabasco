<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashDeduction extends Model
{
    use HasFactory;

    protected $fillable = ['sale_id', 'deducted_amount', 'deduction_reason'];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
