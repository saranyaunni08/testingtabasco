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
        'room_id',  'customer_name',
        'customer_email',
        'customer_contact',
        'sale_amount',
        'area_calculation_type',
        'flat_build_up_area',
        'flat_carpet_area',
        'total_amount',
        'discount_percentage',
        'discount_amount',
        'final_amount',
        'cash_value_percentage',
        'cash_value_amount',
        'additional_amounts',
        'total_cash_value',
        'total_received_amount',
        'partners',
        'partner_distribution',
        'other_expenses',
        'remaining_cash_value',
        'loan_type',
        'installment_frequency',
        'installment_start_date',
        'number_of_installments',
        'installment_amount',
        'gst_percentage',
        'gst_amount',
        'total_cheque_value_with_gst',
        'received_cheque_value',
        'balance_amount',
    ];

    // public function installments()
    // {
    //     return $this->hasMany(Installment::class);
    // }
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
    // In Sale.php Model
public function installments()
{
    return $this->hasMany(Installment::class, 'sale_id');
}


}
    