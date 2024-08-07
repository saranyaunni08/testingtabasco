<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;


class EditDeleteAuth extends Model
{
    use HasFactory;

    protected $table = 'edit_delete_auth'; 
    protected $fillable = ['username', 'password'];

    protected $hidden = ['password'];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->isDirty('password')) {
                $model->attributes['password'] = Hash::make($model->attributes['password']);
            }
        });
    }
}