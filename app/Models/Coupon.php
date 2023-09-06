<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = ['name', 'discount', 'service', 'active'];

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
