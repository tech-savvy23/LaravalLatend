<?php

namespace App\Models;

use App\Models\Common\DistanceRadius;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = ['pin', 'city', 'state', 'body', 'landmark', 'latitude', 'longitude', 'user_id', 'house_no'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeRangeAddress($query,$distance)
    {
        $partner = auth('partner')->user();

        if (DistanceRadius::distance($this->latitude, $this->longitude, $partner->latitude, $partner->longitude) < 2) {
            return  $query;
        }
    }

    public function cityWithName()
    {
        return $this->hasOne(City::class, 'name', 'city');
    }

    public function stateWithName()
    {
        return $this->hasOne(State::class, 'name', 'state');
    }
}
