<?php

namespace App\Http\Resources;

use App\Models\Booking;
use App\Models\Partner;
use App\Models\PartnerPrice;
use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
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
            'address' => $this->address,
            'uid' => Booking::BOOKING_UNIQUE_ID . "-{$this->id}",
            'report_uid' => Booking::REPORT_UNIQUE_ID . "-{$this->id}",
            'auditor' => $this->auditor(),
            'contractor' => $this->contractor(),
            'time' => $this->booking_time->format('H:i A'),
            'service' => $this->booking_service->service,
            'space' => $this->booking_space->space,
            'spaceType' => $this->booking_space->spaceType,
            'date' => $this->booking_time->format('d M, Y'),
            'status' => $this->status,
            'area' => $this->area,
            'auditor_price' => $this->partnerPriceCalculation(Partner::TYPE_AUDITOR),
            'contractor_price' => $this->partnerPriceCalculation(Partner::TYPE_CONTRACTOR),
            'amount' => $this->area_price === 0 ? ($this->area ? $this->area->amount * $this->area_number : null) : $this->area_price,
            'payment_id' => $this->payment->count() > 0 ? $this->payment->first()->id : null,
            'payment_status' => $this->payment->count() > 0 ? $this->payment->first()->status : null,
            'payment_mode' => $this->payment->count() > 0 ? $this->payment->first()->mode : null,
            'otp_status' => $this->otp_status,
            'otp' => $this->otp->count() > 0 ? $this->otp->first()->otp : null,
            'payment' => $this->payment ? $this->payment : null,
            'auditor_payment' => $this->payment->first(),
            'contractor_payment' => $this->payment->count() == 2 ? $this->payment[1] : null,
            $this->mergeWhen(auth('admin')->check(), [
                'customer' => $this->user,
            ]),
            'products_price' => $this->productPriceWithTax($this->productsPrice),
            'tax' => Product::GST,
            'file' => new BookingFileResource($this->bookingFile),
            'reschedule_status' => $this->reschedule_status,
            'reschedule_request' => $this->rescheduleRequest->where('status', 1)->first(),
            'all_reschedule' => $this->rescheduleRequest,
            'user_gst' => $this->user->gst,
            'area_no' => $this->area_number,
        ];
    }

    public function auditor()
    {
        $auditor = $this->booking_allottee->where('allottee_type', Partner::TYPE_AUDITOR)->where('status', 1)->first();
        return $auditor ? new PartnerResource($auditor->partner) : null;
    }

    public function contractor()
    {
        $contractor = $this->booking_allottee->where('allottee_type', Partner::TYPE_CONTRACTOR)->where('status', 1)->first();
        return $contractor ? new PartnerResource($contractor->partner) : null;
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
        if ($this->address) {
            if ($this->address->cityWithName()->count() == 0) {

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
                if ($this->address != null) {
                    if ($this->address->cityWithName) {
                        if($this->address->cityWithName->cityWisePartnerPrice) {
                            $partner_price = $this->address->cityWithName->cityWisePartnerPrice
                                ->where('type', $type)->first();
                            $price = !$partner_price ? $price : $partner_price;
                        }
                    }
                }


            }

            return $price;
        }

    }
}
