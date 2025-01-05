<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstallmentPayment extends Model
{
    use HasFactory;

    protected $fillable = ['installment_id', 'paid_amount', 'payment_date'];

    // Define the relationship to Installment
    public function installment()
    {
        return $this->belongsTo(Installment::class);
    }
}
