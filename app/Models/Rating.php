<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = ['booking_id', 'rating', ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
