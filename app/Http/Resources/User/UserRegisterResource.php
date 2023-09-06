<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserRegisterResource extends JsonResource
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
            'mobile' => $this->mobile,
            'email' => $this->email,
            'first_name' => $this->userProfile->f_name,
            'last_name' => $this->userProfile->l_name,
        ];
    }
}
