<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'room_number', 'room_floor', 'room_type', 'build_up_area', 'carpet_area', 'flat_rate',
        'super_build_up_price', 'carpet_area_price', 'shop_number', 'shop_type', 'shop_area',
        'shop_rate', 'shop_rental_period', 'parking_number', 'parking_type', 'parking_area',
        'parking_rate', 'space_name', 'space_type', 'space_area', 'space_rate', 'kiosk_name',
        'kiosk_type', 'kiosk_area', 'kiosk_rate', 'chair_name', 'chair_type', 'chair_material',
        'chair_price', 'building_id', 'flat_model', 'sale_amount', 'expected_carpet_area_price',
        'expected_super_buildup_area_price', // Ensure these are included
    ];

       public function building()
    {
        return $this->belongsTo(Building::class);
    }
}
