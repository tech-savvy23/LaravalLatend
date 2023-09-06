<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingGst extends Model
{
    protected $fillable = ['booking_id', 'organisation_name', 'gst_no'];

}
