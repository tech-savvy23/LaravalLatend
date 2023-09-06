<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingAllottee extends Model
{
    protected $fillable = ['booking_id', 'allottee_id', 'allottee_type', 'status', ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class, 'allottee_id');
    }

    public function partnerPrice()
    {
        return $this->belongsTo(PartnerPrice::class, 'type', 'allottee_type');
    }
}
