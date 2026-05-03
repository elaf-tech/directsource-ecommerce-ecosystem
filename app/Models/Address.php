<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = ['user_id', 'country', 'region','city','street_address','latitude','longitude'];
 
    public function user()
    {
        return $this->belongsTo(User::class);
    }
 
    public function orders() {
        return $this->hasMany(Order::class, 'address_id');
    }
    public function getFullAddressAttribute() {
        return "{$this->country}, {$this->region}, {$this->city}, {$this->street_address}";
    }
    
    
}
