<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Space extends Model
{
    protected $fillable = ['name', 'thumbnail', ];

    public function type()
    {
        return $this->hasMany(SpaceType::class);
    }

    public function bookings()
    {
        return $this->hasMany(BookingSpace::class);
    }
}
