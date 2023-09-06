<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerPrice extends Model
{
    protected $fillable = ['state_id', 'city_id', 'price', 'type', ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
