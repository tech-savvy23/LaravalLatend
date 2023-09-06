<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetArea extends Model
{
    protected $fillable = ['title', ];

    public function item()
    {
        return $this->hasMany(AssetItem::class, 'area_id');
    }
}
