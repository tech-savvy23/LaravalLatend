<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingReportMessage extends Model
{
    protected $fillable = ['booking_id', 'booking_report_id', 'report_option_message_id', ];

    public function bookingReport()
    {
        return $this->belongsTo(BookingReport::class);
    }

    public function reportOptionMessage()
    {
        return $this->belongsTo(ReportOptionMessage::class);
    }
}
