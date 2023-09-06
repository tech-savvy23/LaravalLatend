<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpaceType extends Model
{
    protected $fillable = ['space_id', 'name', 'thumbnail', 'value'];

    public function space()
    {
        return $this->belongsTo(Space::class);
    }
}
