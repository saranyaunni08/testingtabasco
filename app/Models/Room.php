<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        // Add all your fillable attributes here
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
        'building_id',
        'flat_model',
        'sale_amount',
        'total_sq_ft',
        'total_sq_rate',
        'expected_amount',
        'total_amount',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($room) {
            // Calculate total_sq_ft and total_sq_rate
            $total_sq_ft = ($room->build_up_area ?? 0) + ($room->carpet_area ?? 0);
            $total_sq_rate = ($room->super_build_up_price ?? 0) + ($room->carpet_area_price ?? 0);

            // Calculate expected_amount and total_amount
            $expected_amount = $total_sq_ft * $total_sq_rate;
            $total_amount = $total_sq_ft * ($room->sale_amount ?? 0);

            // Set derived attributes
            $room->total_sq_ft = $total_sq_ft;
            $room->total_sq_rate = $total_sq_rate;
            $room->expected_amount = $expected_amount;
            $room->total_amount = $total_amount;
        });
    }

    public function building()
    {
        return $this->belongsTo(Building::class);
    }
}
