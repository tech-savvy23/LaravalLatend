<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingCancel extends Model
{
    protected $fillable = ['booking_id', 'user_id', 'user_type', 'reason', ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
