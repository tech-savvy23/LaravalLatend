<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'first_name'        => $this->first_name,
            'last_name'         => $this->last_name,
            'email'             => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'mobile_verified'   => $this->mobile_verified,
            'mobile'            => $this->mobile,
            'image'             => $this->image,
            'gst'               => $this->gst,
            'pan'               => $this->pan,
            'active'            => $this->active,
        ];
    }
}
