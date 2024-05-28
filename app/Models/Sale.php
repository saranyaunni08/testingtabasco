<?php
// app/Models/Sale.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'room_id',
        'customer_name',
        'customer_contact',
        'customer_email',
        'sale_amount',
    ];

    // Define the relationship between Sale and Room
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
