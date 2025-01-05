<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ExtraExpensePayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'cash_installment_id',
        'description',
        'percentage',
        'amount',
    ];
    // Define relationships
    public function cashInstallment()
    {
        return $this->belongsTo(CashInstallment::class);
    }
}