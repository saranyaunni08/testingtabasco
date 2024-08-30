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
        'room_id', 'customer_name', 'customer_contact', 'customer_email', 'sale_amount', 'calculation_type',
        'gst_percent', 'advance_payment', 'installment_date', 'advance_amount', 'payment_method', 'cheque_id',
        'transfer_id', 'last_date', 'discount_percent', 'installments', 'total_amount', 'area_calculation_type',
        'parking_rate_per_sq_ft', 'total_sq_ft_for_parking', 'parking_amount', 'room_rate', 'total_with_gst',
        'total_with_discount', 'remaining_balance', 'cash_in_hand_percent', 'in_hand_amount', 'fine_amount','cuspayment_method',
        'custransfer_id','cuscheque_id', 'cash_in_hand_partner_name',
        'cash_in_hand_status',
        'cash_in_hand_paid_amount',
    ];

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
    