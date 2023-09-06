<?php

namespace App\Models;

use App\User;
use Carbon\Carbon;
use App\Models\Common\Otp;
use App\Events\AcceptBookingEvent;
use App\Events\CancelBookingEvent;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    public const STARTED = 0;
    public const AUDITOR_ACCEPTED = 1;
    public const AUDITED = 2;
    public const CONTRACTOR_REQUIRED = 3;
    public const CONTRACTOR_ACCEPTED = 4;
    public const COMPLETED = 5;

    public const BOOKING_UNIQUE_ID = 'BKUI';
    public const REPORT_UNIQUE_ID = 'RPUI';
    public const RADIUS = 50000;

    public const GST = 18;

    protected $fillable = ['address_id', 'area_price', 'user_id', 'booking_time', 'status', 'otp_status', 'area_number', 'area_id', 'contractor_time', 'reschedule_status'];

    protected $casts = [
        'status' => 'integer',
        'otp_status' => 'boolean',
        'reschedule_status' => 'boolean',
        'booking_time' => 'dateTime',
        'contractor_time' => 'dateTime',
    ];

    public function scopeForPartner($query, $partner)
    {
        $booking_status = $partner->type == Partner::TYPE_AUDITOR ? Self::STARTED : Self::CONTRACTOR_REQUIRED;
        return $query->where('status', $booking_status);
    }

    public function scopeWithInCity($query)
    {
        return $query->whereHas('address', function ($q) {
            return $q->where('city', strtolower(auth('partner')->user()->city));
        });
    }

    public function scopeNotPrevAccepted($query)
    {
        if (auth('partner')->user()->type == Partner::TYPE_AUDITOR) {
            return $query->doesntHave('booking_allottee')->orWhereHas('booking_allottee', function ($q) {
                $q->where('allottee_id', '!=', auth('partner')->id())->where('status', 0);
            });
        }

        return $query->whereHas('booking_allottee', function ($q) {
            $q->where('allottee_id', '!=', auth('partner')->id());
        });
    }

    public function scopePaymentDone($query)
    {
        $type = auth('partner')->user()->type;
        return $query->whereHas('payment', function ($q) use ($type) {
            $q->where('partner_type', $type);
        });
    }


    public function scopeNearBy($query, $distance = 50)
    {
        return $query;
    }

    /**  */
    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function payment()
    {
        return $this->hasMany(Payment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function booking_space()
    {
        return $this->hasOne(BookingSpace::class);
    }

    public function booking_service()
    {
        return $this->hasOne(BookingService::class);
    }

    public function booking_allottee()
    {
        return $this->hasMany(BookingAllottee::class);
    }

    public function reports()
    {
        return $this->hasMany(BookingReport::class);
    }

    public function assets()
    {
        return $this->hasOne(BookingAsset::class);
    }

    public function otp()
    {
        return $this->morphMany(Otp::class, 'for');
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'booking_products');
    }

    public function productsPrice()
    {
        return $this->belongsToMany(Product::class, 'booking_products')->withPivot('id', 'quantity');
    }

    public function booking_multiple_checklist()
    {
        return $this->hasMany(BookingMultipleChecklist::class, 'booking_id');
    }

    public function booking_devices()
    {
        return $this->hasMany(BookingDevice::class, 'booking_id');
    }

    public function totalPriceWithTax()
    {
        $total_price = 0;
        $gst = Product::GST;

        $products = $this->products()->withPivot('id', 'quantity')->get();

        foreach ($products as $product) {

            $total_price += $product->pivot->quantity * $product->price;
        }

        return ($total_price + (number_format((float)$total_price * $gst / 100, 2, '.', '')));
    }

    public function partnerprice($type)
    {
        $price = PartnerPrice::where('type', $type)->first();

        $city = City::where('name', $this->address->city)->first();

        if (!$city) {

            $state = State::where('name', $this->address->state)->first();

            if ($state) {

                $pp = PartnerPrice::where('state_id', $state->id)
                    ->whereNull('city_id')
                    ->where('type', $type)->first();
                if ($pp) {

                    $price = $pp;
                }
            }

        } else {

            $partner_price = PartnerPrice::where('city_id', $city->id)
                ->where('type', $type)->first();

            $price = !$partner_price ? PartnerPrice::first() : $partner_price;
        }
        return $price;
    }

    public static function store($request)
    {
        $user = $request->user_id ? User::find($request->user_id) : auth()->user(); // this is customer;

        if ($request->has('address')) {
            $address = $user->address()->create($request->address);
            $address_id = $address->id;
        } else {
            $address_id = $request->address_id;
        }
        $areaPrice = self::areaPrice($request, $address_id);
        $booking = self::create([
            'address_id' => $address_id,
            'user_id' => $user->id,
            'booking_time' => $request->booking_time,
            'status' => false,
            'otp_status' => false,
            'area_number' => $request->area_number,
            'area_id' => $request->area_id,
            'area_price' => $areaPrice,
        ]);
        return $booking;
    }

    public function linkService($request)
    {
        $this->booking_service()->create([
            'service_id' => $request->service_id,
            'service_type' => $request->service_type,
            'sub_service_id' => $request->sub_service_id,
        ]);
    }

    public function linkSpace($request)
    {
        $this->booking_space()->create([
            'space_id' => $request->space_id,
            'space_type_id' => $request->space_type_id,
        ]);
    }

    public function accept()
    {
        $partner = auth('partner')->user();
        $booking_status = $partner->type == Partner::TYPE_AUDITOR ? Booking::AUDITOR_ACCEPTED : Booking::CONTRACTOR_ACCEPTED;

        $this->booking_allottee()->create([
            'allottee_id' => $partner->id,
            'allottee_type' => $partner->type,
            'status' => 1,
        ]);

        $this->update(['status' => $booking_status]);

        $this->notifyAccept();
    }

    public function notifyAccept()
    {
        $otp = Otp::generate($this->user, $this);
        $booking_allottee = $this->booking_allottee()->where('status', 1)->first();
        event(new AcceptBookingEvent($booking_allottee, $otp));
    }

    public function cancel()
    {
        $partner = auth('partner')->user();
        $booking_status = $partner->type == Partner::TYPE_AUDITOR ? Self::STARTED : Self::CONTRACTOR_REQUIRED;
        // allottee status false
        $booking_allottee = $this->booking_allottee
            ->where('allottee_id', $partner->id)
            ->where('allottee_type', $partner->type)->first();

        $booking_allottee->update(['status' => 0, 'reschedule_status' => 0]);

//         booking status 0
        $this->update(['status' => $booking_status, 'otp_status' => false]);

//         delete otp
        $otp = Otp::where([
            'model_id' => $this->user->id,
            'model_type' => get_class($this->user),
            'for_type' => get_class($this),
            'for_id' => $this->id,
        ])->delete();

//         confirmation to user and allottee ( email )
        $this->notifyCancel($booking_allottee);
    }

    public function notifyCancel($booking_allottee)
    {
        event(new CancelBookingEvent($this->user, $booking_allottee));
    }

    public function getStateIdAttribute()
    {
        $state = State::whereRaw('LOWER(`name`) LIKE ?', strtolower($this->address->state))->first();
        return $state ? $state->id : $this->state;
    }

    public function getCityIdAttribute()
    {
        $city = City::whereRaw('LOWER(`name`) LIKE ?', strtolower($this->address->city))->first();
        return $city ? $city->id : $this->city;
    }

    /**
     * get Today audits
     * @return mixed
     */
    public static function todayAudits()
    {
        return self::whereDate('booking_time', Carbon::now())->get();
    }

    /**
     * get all audited audits
     * @return mixed
     */
    public static function todayAuditedBookings()
    {
        return self::whereDate('booking_time', Carbon::now())
            ->where('status', self::AUDITED)
            ->get();
    }

    /**
     * get all today bookings (which is book today)
     * @return mixed
     */
    public static function todayCreatedBookings()
    {
        return self::whereDate('created_at', Carbon::now())->get();
    }

    public function bookingFile()
    {
        return $this->hasOne(BookingFile::class);
    }

    public function rescheduleRequest()
    {
        return $this->hasMany(RescheduleRequest::class, 'booking_id');
        // if ($this->reschedule_status) {
        //     return RescheduleRequest::where('booking_id', $this->id)
        //         ->where('status', true)
        //         ->orderByDesc('created_at')
        //         ->first();
        // }
    }

    public function bookingBlock()
    {
        return $this->hasOne(BookingBlock::class, 'booking_id');
    }

    private static function areaPrice($request, $addressId)
    {
        $homeSpaceArea = [
            '1' => 1500,
            '2' => 2000,
            '3' => 2500,
            '4' => 3000,
            '5' => 3500,
            '6' => 4000,
            '7' => 4500,
            '8' => 5500,
            '9' => 6000,
            '10' => 6500,
            '11' => 7000,
            '12' => 7500,
            '13' => 8000,
            '14' => 8500,
            '15' => 9000
        ];
        $homeSpaceFactoryCity = [
            'Metro' => 1,
            'Non' => 0.75
        ];

        $space = Space::find($request->space_id);

        if ($space->name === 'HomeSpace') {

            $address = Address::find($addressId);
            $city = City::where('name', $address->city)->first();

            if ($city) {

                $factoryCity = FactoryCity::where('city_id', $city->id)->first();

                if ($factoryCity) {
                    $metroAndNonMetro = MetroAndNonMetro::where('space_id', $space->id)->where('type', 'metro')->first();
                    return $homeSpaceArea[$request->area_number] * (float)$metroAndNonMetro->value;
                }

            }
            $metroAndNonMetro = MetroAndNonMetro::where('space_id', $space->id)->where('type', 'non-metro')->first();
            return $homeSpaceArea[$request->area_number] *  (float)$metroAndNonMetro->value;
        }
        $spaceType = SpaceType::find($request->space_type_id);
        $workSpaceFactoryCity = [
            'Metro' => 1,
            'Non' => 0.8
        ];
        $formulaValue = 1;

        if ($request->area_number < 1000) {
           $formulaValue =  (2500 + (int)$request->area_number);
        }
        elseif ($request->area_number >= 1000 && $request->area_number < 2000) {
            $formulaValue =  (3500 + ((int)$request->area_number - 1000));
        }
        elseif ($request->area_number >= 2000 && $request->area_number < 5000) {
            $formulaValue =  (4500 + (((int)$request->area_number - 2000) / 2));
        }
        elseif ($request->area_number >= 5000 && $request->area_number < 10000) {
            $formulaValue =  (6000 + (((int)$request->area_number - 5000) / 2));
        }
        elseif ($request->area_number >= 10000 && $request->area_number < 50000) {
            $formulaValue =  (8500 + (((int)$request->area_number - 10000) / 5));
        }
        elseif ($request->area_number >= 50000 && $request->area_number < 100000) {
            $formulaValue =  (16500 + (((int)$request->area_number - 50000) / 10));
        }
        elseif ($request->area_number >= 100000 && $request->area_number < 200000) {
            $formulaValue =  (21500 + (((int)$request->area_number - 100000) / 20));
        }

        $address = Address::find($addressId);
        $city = City::where('name', $address->city)->first();
        if ($city) {

            $factoryCity = FactoryCity::where('city_id', $city->id)->first();

            if ($factoryCity) {
                $metroAndNonMetro = MetroAndNonMetro::where('space_id', $space->id)->where('type', 'metro')->first();
                return  $formulaValue * $spaceType->value * (float)$metroAndNonMetro->value;
            }
        }
        $metroAndNonMetro = MetroAndNonMetro::where('space_id', $space->id)->where('type', 'non-metro')->first();
        return  $formulaValue * $spaceType->value * (float)$metroAndNonMetro->value;
    }
}
