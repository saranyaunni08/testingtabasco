<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banks extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'account_number', 'ifsc_code', 'branch', 'account_holder_name',
        'address', 'city', 'country', 'contact_number', 'email_address',
    ];}
