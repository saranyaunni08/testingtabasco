<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;

    protected $table = 'buildings'; // Define the table name

    protected $fillable = [
        'building_name',
        'no_of_floors',
        'building_address',
        'street',
        'city',
        'pin_code',
        'state',
        'country',
        'super_built_up_area',         // Super Built-Up Area (sq ft)
        'carpet_area',                 // Carpet Area (sq ft)
        'super_built_up_area_sq_m',    // Super Built-Up Area (sq m)
        'carpet_area_sq_m',            // Carpet Area (sq m)
        'building_amenities',
        'additional_amenities',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
    public function sales()
    {
        return $this->hasMany(Sale::class, 'building_id');
    }
}
