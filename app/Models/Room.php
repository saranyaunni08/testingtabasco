<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_number',
        'room_floor',
        'room_type',
        'build_up_area',
        'carpet_area',
        'flat_rate',
        'super_build_up_price',
        'carpet_area_price',
        'shop_number',
        'shop_type',
        'shop_area',
        'shop_rate',
        'shop_rental_period',
        'parking_number',
        'parking_type',
        'parking_area',
        'parking_rate',
        'space_name',
        'space_type',
        'space_area',
        'space_rate',
        'kiosk_name',
        'kiosk_type',
        'kiosk_area',
        'kiosk_rate',
        'chair_name',
        'chair_type',
        'chair_material',
        'chair_price',
        // Add other fillable fields as needed
    ];
}
