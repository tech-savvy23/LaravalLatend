<?php

namespace App\Http\Resources;

use App\Models\Area;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            'id'        => $this->id,
            'name'      => $this->name,
            'thumbnail' => $this->thumbnail,
            'body'      => $this->description,
            'area'      => Area::select('type', 'amount', 'id')->get(),
        ];
    }
}
