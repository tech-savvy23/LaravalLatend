<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PartnerResource extends JsonResource
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
            'id'                 => $this->id,
            'name'               => $this->name,
            'email'              => $this->email,
            'email_verified_at'  => $this->email_verified_at,
            'phone'              => $this->phone,
            'city'               => $this->city,
            'state'              => $this->state,
            'pin'                => $this->pin,
            'latitude'           => $this->latitude,
            'longitude'          => $this->longitude,
            'type'               => $this->type,
            'active'             => $this->active,
            'image'              => $this->media ? $this->media->name : null,
            'police_verified_at' => $this->police_verified_at,
            'total_earning'      => $this->totalEarning(),
        ];
    }
}
