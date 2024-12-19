<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleReturn extends Model
{
    protected $fillable = ['sale_id', 'returned_amount', 'description','return_date'];

    
    protected $casts = [
        'return_date' => 'datetime',
    ];
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
