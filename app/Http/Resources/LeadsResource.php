<?php

namespace App\Http\Resources;

use App\Models\Booking;
use App\Models\Partner;
use App\Models\Product;
use App\Http\Resources\User\UserResource;
use App\Models\City;
use App\Models\PartnerPrice;
use App\Models\State;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => new UserResource($this->user),
            'uid' => Booking::BOOKING_UNIQUE_ID . "-{$this->id}",
            'report_uid' => Booking::REPORT_UNIQUE_ID . "-{$this->id}",
            'address' => $this->address,
            'booking_space'   => $this->booking_space ? $this->booking_space->space : null,
            'booking_service' => $this->booking_service ? $this->booking_service->service : null,
            'appointment_date' => $this->booking_time->format('d M'),
            'appointment_time' => $this->booking_time->format('h:i a'),
            'contractor_date' => $this->contractor_time ? $this->contractor_time->format('d M') : null,
            'contractor_time' => $this->contractor_time ? $this->contractor_time->format('h:i a') : null,
            'status' => $this->status,
            'created_at' => $this->created_at->diffForHumans(),
            'otp_status' => $this->otp_status,
            'payments' => $this->payment->where('partner_type', 'Auditor') ? $this->payment->where('partner_type', 'Auditor')->first() : null,
            'contractor_payments' => $this->payment->where('partner_type', 'Contractor') ? $this->payment->where('partner_type', 'Contractor')->first() : null,
            'area' => $this->area,
            'area_number' => $this->area_number,
            'auditor_price' => $this->partnerPriceCalculation(Partner::TYPE_AUDITOR),
            'contractor_price' => $this->partnerPriceCalculation(Partner::TYPE_CONTRACTOR),
            'amount' => $this->payment->where('partner_type', 'Auditor')->count() > 0 ? (float)$this->payment->where('partner_type', 'Auditor')->first()->amount : (float) $this->payment->first()->amount,
            'products_price' => $this->productPriceWithTax($this->productsPrice),
            'tax' => Product::GST,
            'reschedule_status' => $this->reschedule_status,
            'reschedule_request' => $this->rescheduleRequest->where('status', 1)->first(),
            'new_booking_space' => $this->booking_space ? array_merge(['space' => $this->booking_space->space->name], [
                'spaceType' => $this->booking_space->spaceType ? $this->booking_space->spaceType->name : null
            ]) : null,
        ];
    }

    public function productPriceWithTax($products)
    {
        $total_price = 0;
        $gst = Product::GST;
        foreach ($products as $product) {

            $total_price += $product->pivot->quantity * $product->price;
        }
        return ($total_price + (number_format((float)$total_price * $gst / 100, 2, '.', '')));
    }

    public function partnerPriceCalculation($type)
    {
        $price = PartnerPrice::where('type', $type)->first();

        $city = $this->address->cityWithName;

        if (!$city) {

            $state = $this->address->stateWithName;

            if ($state) {

                if ($state->stateWisePartnerPrice) {

                    $pp = $state->stateWisePartnerPrice
                        ->whereNull('city_id')
                        ->where('type', $type)->first();

                    if ($pp) {

                        $price = $pp;
                    }
                }
            }

        } else {

            if ($city->cityWisePartnerPrice) {

                $partner_price = $city->cityWisePartnerPrice
                    ->where('type', $type)->first();
                $price = !$partner_price ? $price : $partner_price;
            }

        }

        return $price;
    }
}
