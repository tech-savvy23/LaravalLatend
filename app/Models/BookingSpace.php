<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingSpace extends Model
{
    protected $fillable = ['booking_id', 'space_id', 'space_type_id', ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function space()
    {
        return $this->belongsTo(Space::class);
    }

    public function spaceType()
    {
        return $this->belongsTo(SpaceType::class, 'space_type_id');
    }
}
