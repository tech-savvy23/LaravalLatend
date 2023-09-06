<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetItem extends Model
{
    protected $fillable = ['area_id', 'description', ];

    public function area()
    {
        return $this->belongsTo(AssetArea::class);
    }

    public function booking_asset()
    {
        return $this->hasMany(BookingAsset::class);
    }
}
