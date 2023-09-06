<?php

namespace App\Http\Resources;

use App\BookingColorCode;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $bookingReport = $this->bookingReport->where('booking_id', request('booking_id'))->where('multi_checklist_id', request('multi_id'))->first();
        return [
            'id' => $this->id,
            'title' => $this->title,
            'parent_id' => $this->parent_id,
            'selected_option_id' => $this->getSelectedOption($bookingReport),
            'result' => $this->getResult($bookingReport),
            'booking_report_id' => $this->getBookingReportId($bookingReport),
            'selected_message_ids' => $this->getBookingReportMessages($bookingReport),
            'parent_option_id' => $this->parent_option_id,
            'options' => $this->getOptions(),
            'images' => $this->getImages($bookingReport),
            'observation' => $this->getObservation($bookingReport),
            'booking_color_code' => $this->getResultColorCode($bookingReport),
        ];
    }

    public function getBookingReportMessages($bookingReport)
    {
        return $bookingReport ? $bookingReport->messages->pluck('id') : [];
    }

    public function getOptions()
    {
        return $this->options->map(function ($option) {
            return [
                'id' => $option->id,
                'title' => $option->title,
                'messages' => $option->messages ? $option->messages->map(function ($msg) {
                    return ['value' => $msg->message, 'id' => $msg->id];
                }) : null,
            ];
        });
    }

    public function getSelectedOption($bookingReport)
    {
        return $bookingReport ? $bookingReport['selected_option_id'] : null;
    }

    public function getObservation($bookingReport)
    {
        return $bookingReport ? $bookingReport['observation'] : null;
    }

    public function getImages($bookingReport)
    {
        return $bookingReport ? $bookingReport['media'] : [];
    }

    public function getBookingReportId($bookingReport)
    {
        return $bookingReport ? $bookingReport['id'] : null;
    }

    public function getResult($bookingReport)
    {
        return $bookingReport ? $bookingReport['result'] : null;
    }

    public function getResultColorCode($bookingReport)
    {
        if ($bookingReport != null) {
            $bookingColorCode = BookingColorCode::where('booking_report_id', $bookingReport['id'])->first();
            return $bookingColorCode != null ? [
                'id' => $bookingColorCode->id,
                'color_code_id' => $bookingColorCode->color_code_id,
            ] : null;
        }
        return null;

    }
}
