<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesChequeReturn extends Model
{
    use HasFactory;

    protected $table = 'sales_cheque_returns';

    protected $fillable = [
        'sale_id',
        'returned_amount',
        'description',
        'return_date',
    ];
}
