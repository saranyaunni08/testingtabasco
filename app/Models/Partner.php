<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    // Add your fillable fields here
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'address',
        'date_of_birth',
        'gender',
        'nationality',
        'identification_number',
    ];

    // Other model properties and methods...

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
    public function partnerDistributions()
    {
        return $this->hasMany(PartnerDistribution::class);
    }

    public function partnerCashInstallments()
    {
        return $this->hasMany(PartnerCashInstallment::class);
    }

}