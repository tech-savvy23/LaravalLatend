<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FeedbackResource extends JsonResource
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
            'id'          => $this->id,
            'partner'     => $this->partner,
            'user'        => $this->user,
            'booking_id'  => $this->booking_id,
            'service'     => $this->booking->booking_service->service,
            'space'       => $this->booking->booking_space->space,
            'created_at'  => $this->created_at->format('M d,Y h:m'),
            'body'        => $this->body,
            'rating'      => $this->rating,
        ];
    }
}
