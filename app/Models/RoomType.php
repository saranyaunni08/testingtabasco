<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    use HasFactory;

    // Specify the table associated with the model (optional if the table name is not plural of the model name)
    protected $table = 'room_types';

    // Allow mass assignment for the 'name' column
    protected $fillable = ['name'];

    // Define any relationships or additional functionality here if needed
}
