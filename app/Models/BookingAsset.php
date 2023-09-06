<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingAsset extends Model
{
    protected $fillable = ['asset_item_id', 'booking_id', 'number', 'phase', 'voltage', 'current', ];

    public function item()
    {
        return $this->belongsTo(AssetItem::class, 'asset_item_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
