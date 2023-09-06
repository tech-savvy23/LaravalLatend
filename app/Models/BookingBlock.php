<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingBlock extends Model
{
    protected $fillable = ['booking_id', 'partner_id'];

    public static function store($request)
    {
        self::create([
            'booking_id' => $request->booking_id,
            'partner_id' => auth('partner')->user()->id
        ]);
    }
}
