<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['description', 'uom', 'price', 'maker', 'state_id', 'city_id'];
    public const GST               = 18;
    public function scopeActive($query)
    {
        return $query->whereActive(true);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_products');
    }
}
