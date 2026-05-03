<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = ['user_id', 'company_name', 'business_type','address','logo','commercial_registration_number','identity_number','bank_account'];
 
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function products()
{
    return $this->hasMany(Product::class);
}
public function companies()
{
    return $this->hasMany(Company::class, 'supplier_id');
}

}


