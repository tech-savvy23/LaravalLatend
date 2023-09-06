<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MetroAndNonMetroResource extends JsonResource
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
            'space_id' => $this->space_id,
            'space' => $this->space->name,
            'type'=> $this->type,
            'value' => $this->value,
        ];
    }
}
