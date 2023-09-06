<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = ['booking_id', 'user_id', 'partner_id', 'rating', 'body', ];

    public function scopeOfUser($query, $user_id = null)
    {
        $user_id  = $user_id ? $user_id : auth()->id();
        return $query->where('user_id', $user_id);
    }

    public function scopeForPartner($query, $partner_id)
    {
        return $query->where('partner_id', $partner_id);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
