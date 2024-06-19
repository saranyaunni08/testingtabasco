<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterSetting extends Model
{
    use HasFactory;

    protected $fillable = ['gst_shop', 'gst_flat', 'advance_payment_days', 'parking_fixed_rate', 'parking_rate_per_sq_ft'];
}
