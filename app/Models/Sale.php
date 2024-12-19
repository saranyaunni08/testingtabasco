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
        'room_id',
        'customer_name',
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
        'cash_installment_value',      // Add this field
        'cash_loan_type',              // Add this field
        'cash_installment_frequency',  // Add this field
        'cash_installment_start_date', // Add this field
        'cash_no_of_installments',     // Add this field
        'cash_installment_amount',     // Add this field
        'loan_type',
        'installment_frequency',
        'installment_start_date',
        'number_of_installments',
        'installment_amount',
        'gst_percentage',
        'gst_amount',
        'total_cheque_value', 
        'total_cheque_value_with_additional',
        'cheque_distribution',
        'cheque_expense_descriptions',
        'cheque_expense_amounts',
        'partner_percentages',
        'partner_amounts',
        'total_cheque_value_with_gst',
        'received_cheque_value',
        'balance_amount',
        'other_loan_description',
        'installment_date',
        'no_of_installments',
        'grand_total_amount',
        'parking_floor',
        'parking_id',
        'parking_amount_cheque',
        'parking_amount_cash',
        'cheque_description',
        'expense_descriptions',
        'expense_percentages',
        'expense_amounts',
        'other_loan_description_cash',

        'exchangestatus',
        'exchange_sale_id',

        'cancel_description',
        'status',
    ];
    

    protected $casts = [
        'partner_distribution' => 'array',  
        'partner_percentages' => 'array',    
        'partner_amounts' => 'array',         
        'cheque_expense_descriptions' => 'array', // New field
        'cheque_expense_amounts' => 'array',      // New field
    ];

    // public function installments()
    // {
    //     return $this->hasMany(Installment::class);
    // }
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id'); // Assumes `room_id` is the foreign key in `sales` table
    }
    

    // In Sale.php Model
public function installments()
{
    return $this->hasMany(Installment::class, 'sale_id');
}

public function partnerDistributions()
{
    return $this->hasMany(PartnerDistribution::class);
}
public function cashExpenses()
{
    return $this->hasMany(CashExpense::class, 'sale_id');
}
public function building()
{
    return $this->belongsTo(Building::class);
}
public function cash_installments()
    {
        return $this->hasMany(CashInstallment::class); // Adjust based on your actual model
    }
    public function cashInstallments()
{
    return $this->hasMany(CashInstallment::class, 'sale_id');  // Adjust the relationship based on your schema
}

    public function exchangedSale()
    {
        return $this->belongsTo(Sale::class, 'exchange_sale_id');
    }
    
    public function returns()
{
    return $this->hasMany(SaleReturn::class);
}
public function cashDeductions()
{
    return $this->hasMany(CashDeduction::class);
}

}
    
