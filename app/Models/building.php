<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class building extends Model
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
        'super_built_up_area',         
        'carpet_area',                 
        'super_built_up_area_sq_m',    
        'carpet_area_sq_m',            
        'building_amenities',
        'additional_amenities',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
