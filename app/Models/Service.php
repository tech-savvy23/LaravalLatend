<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['name', 'thumbnail', 'description'];

    public function booking_service()
    {
        return $this->hasMany(BookingService::class);
    }
}
