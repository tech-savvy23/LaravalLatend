<?php

namespace App\Http\Resources;

use App\BookingColorCode;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingDeviceResource extends JsonResource
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
            'id'                => $this->id,
            'booking_id'        => $this->booking_id,
            'checklist_type_id' => $this->checklist_type_id,
            'title'             => $this->title,
            'value'             => $this->value,
            'checklist_id'      => $this->checklist_id,
            'result'            => $this->result,
            'images'            => $this->media,
            'booking_color_code' => $this->getResultColorCode($this->bookingColorCode),
        ];
    }

    public function getResultColorCode($bookingColorCode)
    {
        return $bookingColorCode != null ? [
            'id' => $bookingColorCode->id,
            'color_code_id' => $bookingColorCode->color_code_id,
        ] : null;

    }
}
