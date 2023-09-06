<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = ['model_id', 'model_type', 'name'];

    /**
     * Get the owning commentable model.
     */
    public function model()
    {
        return $this->morphTo();
    }
}
