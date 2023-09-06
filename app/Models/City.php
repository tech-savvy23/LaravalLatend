<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = ['name', 'state_id'];

    public function cityWisePartnerPrice()
    {
        return $this->hasOne(PartnerPrice::class, 'city_id', 'id');
    }
}
