<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingMultipleChecklist extends Model
{
    /** @var */
    protected $guarded = [];


    public static function store()
    {
        $booking_multiple_checklist = self::create(request()->all());

        return response()->json([
            'data' => $booking_multiple_checklist
        ], 201);
       
    }

    public function bookingReports()
    {
        return $this->hasMany(BookingReport::class, 'multi_checklist_id', 'id');
    }
}
