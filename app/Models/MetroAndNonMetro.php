<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetroAndNonMetro extends Model
{
    protected $fillable = ['space_id', 'type', 'value'];

    public static function store($request)
    {
        self::create([
            'space_id' => $request->space_id,
            'type' => $request->type,
            'value' => $request->value,
        ]);
    }

    public static function change($request, $id)
    {
        self::find($id)->update([
            'space_id' => $request->space_id,
            'type' => $request->type,
            'value' => $request->value,
        ]);
    }

    public function space(){
        return $this->belongsTo(Space::class, 'space_id');
    }
}
