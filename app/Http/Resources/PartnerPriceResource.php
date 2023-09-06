<?php

namespace App\Http\Resources;

use App\Models\Partner;
use Illuminate\Http\Resources\Json\JsonResource;

class PartnerPriceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return  [
            'id'       => $this->id,
            'state'    => $this->state,
            'state_id' => $this->state_id,
            'city_id'  => $this->city_id,
            'city'     => $this->city,
            'price'    => $this->price,
            'type'     => $this->type == Partner::TYPE_AUDITOR ? Partner::TYPE_AUDITOR : Partner::TYPE_CONTRACTOR,
        ];
    }
}
