<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
            'house_no' => $this->house_no,
            'pin' => $this->pin,
            'city'=> $this->city,
            'state' => $this->state,
            'body' => $this->body,
            'landmark' => $this->landmark
            ];
    }
}
