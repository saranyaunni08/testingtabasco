<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'room_id', 'customer_name', 'customer_email', 'customer_contact', 'sale_amount', 
        'area_calculation_type', 
        'calculation_type', 'parking_rate_per_sq_ft', 'total_sq_ft_for_parking', 
        'gst_percent', 'advance_payment', 'advance_amount', 'payment_method', 
        'transfer_id', 'cheque_id', 'last_date', 'discount_percent',
    ];


    public function room()
{
    return $this->belongsTo(Room::class, 'room_id');
}
    
}
