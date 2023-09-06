<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RescheduleReason extends Model
{
    protected $guarded = [];

    /**
     * Store reasons
     * 
     * @return self
     */
    public static function store()
    {
        return self::create(request()->all());
    }

     /**
     * Update reasons
     * 
     * @return self
     */
    public static function updateData($id)
    {
        $rechdeule_reason =  self::find($id);
        $rechdeule_reason->update(request()->all());
        
        return $rechdeule_reason;
    }
}
