<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashExpense extends Model
{
    use HasFactory;

    // Specify the table name if it's not the plural of the model name
    protected $table = 'cash_expenses';

    // Specify the fillable attributes
    protected $fillable = [
        'sale_id', // Foreign key linking to the sales table
        'cash_expense_description', // Make sure this matches the column name
        'cash_expense_percentage',   // Make sure this matches the column name
        'cash_expense_amount',       // Make sure this matches the column name
    ];
    

    // Define the relationship with the Sale model (if applicable)
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}