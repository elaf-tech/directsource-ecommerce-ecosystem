<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name',
        'type',
        'address',
        'logo',
        'commercial_registration_number',
        'bank_account'
    ];
}
