<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingReportResource extends JsonResource
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
            'checklist'         => $this->checklist->title,
            'checklist_type'    => $this->checklist->has_type ? $this->type->title : null,
            'report'            => $this->report,
            'selected_option'   => $this->selected_option != null ? $this->selected_option->load('messages') : null,
            'visual_observation'=> $this->observation,
            'result'            => $this->result,
            'images'            => $this->media,
        ];
    }
}
