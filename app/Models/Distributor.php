<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Distributor extends Model
{
    protected $fillable = ['name', 'email', 'mobile', 'password', ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
