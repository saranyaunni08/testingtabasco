<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Room extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'room_number', 'room_type', 'square_footage', 'rate_per_square_foot', 'selling_price'
    ];
}
