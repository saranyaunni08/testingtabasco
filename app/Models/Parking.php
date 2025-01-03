<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parking extends Model
{
    use HasFactory;

    protected $fillable = [
        'slot_number',
        'floor_number',
        'status',
        'purchaser_name',
        'amount',
    ];

   // Define the relationship to Sales model
   public function sales()
   {
       return $this->hasOne(Sale::class, 'parking_id'); // Assuming the foreign key is 'parking_id'
   }

  
}

