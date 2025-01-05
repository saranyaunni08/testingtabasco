<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class PartnerCashInstallment extends Model
{
    use HasFactory;
    protected $fillable = [
        'cashinstallment_payment_id',
        'partner_id',
        'percentage',
        'amount',
    ];
    // Define relationships
    public function cashInstallment()
    {
        return $this->belongsTo(CashInstallment::class);
    }
    public function payment()
    {
        return $this->belongsTo(CashInstallmentPayment::class, 'cashinstallment_payment_id');
    }   
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
    public function cashInstallmentPayment()
    {
        return $this->belongsTo(CashInstallmentPayment::class);
    }
}
