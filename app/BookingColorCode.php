<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookingColorCode extends Model
{
    protected $fillable = ['booking_report_id', 'booking_device_id', 'color_code_id'];

    public function colorCode()
    {
        return $this->belongsTo(ColorCode::class, 'color_code_id', 'id');
    }
}
