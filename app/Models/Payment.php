<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['booking_id', 'partnerprice_id', 'transaction_id', 'service', 'coupon_id', 'amount', 'mode', 'status', 'partner_type', 'partner_price','gst'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}
