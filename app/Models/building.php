<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;

    protected $table = 'building'; // Define the table name

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
        'building_amenities',
        'additional_amenities',
    ];
}
