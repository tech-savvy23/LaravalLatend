<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $fillable = ['name', 'country_id'];

    public function stateWisePartnerPrice()
    {
        return $this->hasOne(PartnerPrice::class, 'state_id', 'id');
    }
}
