<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingFile extends Model
{
    protected $fillable = ['booking_id', 'pdf'];
}
