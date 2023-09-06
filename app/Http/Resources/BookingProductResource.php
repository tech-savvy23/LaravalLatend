<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingProductResource extends JsonResource
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
            'id'                 => $this->id,
            'description'        => $this->description,
            'state'              => $this->state,
            'state_id'           => $this->state_id,
            'city'               => $this->city,
            'city_id'            => $this->city_id,
            'uom'                => $this->uom,
            'price'              => $this->pivot->price,
            'maker'              => $this->maker,
            'bookingproduct_id'  => $this->pivot->id,
            'quantity'           => $this->pivot->quantity,
            'gst'                => Product::GST
        ];
    }
}
