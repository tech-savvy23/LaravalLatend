<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FactoryCity extends Model
{
    protected $fillable = ['space_id', 'city_id', 'value'];

    public static function store($request)
    {
        self::create([
            'space_id' => $request->space_id,
            'city_id' => $request->city_id,
            'value' => $request->value,
        ]);
    }

    public static function change($request, $id)
    {
        self::find($id)->update([
            'space_id' => $request->space_id,
            'city_id' => $request->city_id,
            'value' => $request->value,
        ]);
    }

    public function space(){
        return $this->belongsTo(Space::class, 'space_id');
    }

    public function city(){
        return $this->belongsTo(City::class, 'city_id', 'id');
    }
}
