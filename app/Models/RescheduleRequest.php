<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RescheduleRequest extends Model
{
    protected $fillable = ['allottee_id', 'allottee_type', 'booking_id', 'date_time', 'reason', 'status'];
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}
