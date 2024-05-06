<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'room_number', 'room_type', 'square_footage', 'rate_per_square_foot', 'selling_price'
    ];
}
