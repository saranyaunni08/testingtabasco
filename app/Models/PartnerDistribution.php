<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerDistribution extends Model
{
    protected $fillable = ['sale_id', 'partner_id', 'percentage', 'amount'];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
}
