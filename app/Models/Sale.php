<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    // Define the fillable fields for mass assignment
    protected $fillable = [
        'customer_name',
        'customer_address',
        'customer_street',
        'customer_city',
        'customer_phone',
        'customer_pin',
        'customer_state',
        'customer_country',
        'sale_amount',
        'discount_amount',
        'advance_amount',
        'payment_method',
        'installment_period',
        'installment_amount',
        'payment_using',
        'bank_name',
        'branch',
        'account_num',
        'ifsc',
    ];
}
