<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FactoryCityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'city' => $this->city->name,
            'city_id' => $this->city_id,
            'space' => $this->space->name,
            'space_id' => $this->space_id,
            'value' => $this->value,
        ];
    }
}
