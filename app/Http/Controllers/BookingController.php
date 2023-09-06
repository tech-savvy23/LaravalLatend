<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Partner;
use App\Events\PaymentEvent;
use Illuminate\Http\Request;
use App\Events\NewBookingEvent;
use App\Events\RescheduleEvent;
use App\Models\RescheduleReason;
use App\Models\RescheduleRequest;
use Illuminate\Routing\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Events\DeclineRescheduleEvent;
use Spatie\QueryBuilder\AllowedFilter;
use App\Http\Resources\BookingResource;
use App\Helpers\Filters\FilterByAuditorId;
use App\Helpers\Filters\FilterUserFullName;
use App\Helpers\Filters\FilterByAuditorName;
use App\Events\PartnerRescheduleRequestEvent;
use App\Http\Resources\BookingXlsxResource;
use Symfony\Component\HttpFoundation\Response;

class BookingController extends Controller
{
    public function __construct()
    {
        if (!auth('admin')->check()) {
            $this->middleware('auth:api')->except('show', 'update', 'all', 'bookingStatics', 'partnerBookings', 'declineRescheduleRequest', 'approveRescheduleRequest');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bookings = auth()->user()->bookings()->with('booking_allottee')->latest()->paginate(10);
        return BookingResource::collection($bookings);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        $result = QueryBuilder::for(Booking::class)
                    ->with([
                        'address',
                        'address.cityWithName',
                        'address.stateWithName',
                        'address.cityWithName.cityWisePartnerPrice',
                        'address.stateWithName.stateWisePartnerPrice',
                        'rescheduleRequest',
                        'area',
                        'bookingFile',
                        'user',
                        'otp',
                        'payment',
                        'payment.coupon',
                        'booking_service',
                        'booking_service.service',
                        'booking_space',
                        'booking_space.space',
                        'booking_space.spaceType',
                        'booking_allottee',
                        'booking_allottee.partnerPrice',
                        'booking_allottee.partner',
                        'booking_allottee.partner.media',
                        'productsPrice'
                    ])
                    ->allowedFilters([
                        AllowedFilter::custom('username', new FilterUserFullName()),
                        AllowedFilter::custom('auditor', new FilterByAuditorName()),
                        'address.state',
                        'status',
                        'booking_time'
                    ])
                    ->latest()
                    ->paginate(100);

        return BookingResource::collection($result);
    }


    /**
     * Display a listing of the resource all xlsx.
     *
     * @return \Illuminate\Http\Response
     */
    public function allxlsx()
    {
        $result = QueryBuilder::for(Booking::class)
                    ->with([
                        'user',
                        'address',
                        'booking_allottee',
                        'booking_allottee.partner',
                        'reports',
                        'payment',
                        'rescheduleRequest'
                    ])
                    ->allowedFilters([
                        AllowedFilter::custom('username', new FilterUserFullName()),
                        AllowedFilter::custom('auditor', new FilterByAuditorName()),
                        'address.state',
                        'status',
                        'booking_time'
                    ])
                    ->get();

        return BookingXlsxResource::collection($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Booking $booking)
    {
        return response(['data' => new BookingResource($booking)], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $booking = Booking::store($request);
        $booking->linkSpace($request);
        $booking->linkService($request);

        event(new NewBookingEvent($booking));

        return response(new BookingResource($booking), Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Booking $booking)
    {
        $booking->update($request->all());
        $booking->payment()->where('service', 'cash')->update(['status' => 1, 'gst' => Booking::GST]);
        event(new PaymentEvent($booking));
        return response(['data' => $booking], Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Booking $booking
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Booking $booking
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function requestContractor(Booking $booking)
    {
        $booking->update(['status' => Booking::CONTRACTOR_REQUIRED, 'otp_status' => false]);
        // notify contractors
        return response('request sent', Response::HTTP_ACCEPTED);
    }

    /**
     * @param Booking $booking
     * @return mixed
     */
    public function payments(Booking $booking)
    {
        return $booking->payment->load('coupon');
    }

    /**
     * @param $bookingId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateBookingDate($bookingId, Request $request)
    {
        try {
            $this->updateBookingDateValidation($request);
            $booking      = Booking::find($bookingId);
            $partner_type = '';
            if ($booking) {
                if ($booking->status === Booking::AUDITOR_ACCEPTED || $booking->status === Booking::STARTED) {
                    $booking->update(['booking_time' => Carbon::parse($request->booking_time)->format('Y-m-d H:i:s')]);
                    $partner_type = Partner::TYPE_AUDITOR;
                } elseif ($booking->status === Booking::CONTRACTOR_ACCEPTED) {
                    $booking->update(['contractor_time' => Carbon::parse($request->booking_time)->format('Y-m-d H:i:s')]);
                    $partner_type = Partner::TYPE_CONTRACTOR;
                }
                event(new RescheduleEvent($booking, $partner_type, 'Client'));
                return response()->json([
                    'data' => $booking,
                    'time' => Booking::find($bookingId),
                ], Response::HTTP_OK);
            }
            return response()->json([
                'message' => 'Booking not found',
            ], Response::HTTP_NOT_FOUND);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param Request $request
     */
    protected function updateBookingDateValidation($request)
    {
        $request->validate([
            'booking_time' => 'required| after:yesterday',
        ]);
    }

    /**
     * @return array
     */
    public function bookingStatics()
    {
        return response()->json([
            'today_audits'           => BookingResource::collection(Booking::todayAudits()),
            'today_bookings'         => BookingResource::collection(Booking::todayCreatedBookings()),
            'today_complete_audited' => BookingResource::collection(Booking::todayAuditedBookings()),
        ]);
    }

    /**
     * @param Partner $partner
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function partnerBookings(Partner $partner)
    {
        $result = QueryBuilder::for(Booking::class)
            ->with([ 'address',
            'address.cityWithName',
            'address.stateWithName',
            'address.cityWithName.cityWisePartnerPrice',
            'address.stateWithName.stateWisePartnerPrice',
            'rescheduleRequest',
            'area',
            'bookingFile',
            'user',
            'otp',
            'payment',
            'payment.coupon',
            'booking_service',
            'booking_service.service',
            'booking_space',
            'booking_space.space',
            'booking_space.spaceType',
            'booking_allottee',
            'booking_allottee.partnerPrice',
            'booking_allottee.partner',
            'booking_allottee.partner.media',
            'productsPrice'])
            ->allowedFilters([
                AllowedFilter::custom('username', new FilterUserFullName()),
                AllowedFilter::custom('auditor_id', new FilterByAuditorId()),
                'address.state',
                'status',
            ])
            ->latest()
            ->get();

        return BookingResource::collection($result);
    }

    /**
     * Accept Reschedule Request by user
     * @param Booking $booking
     * @return BookingResource
     */
    public function approveRescheduleRequest(Booking $booking)
    {
        if ($booking) {
            $reschedule_request = $booking->rescheduleRequest->where('status', 1)->first();

            $partner_type = $reschedule_request->allottee_type;

            if ($reschedule_request->allottee_type === Partner::TYPE_CONTRACTOR) {
                $booking->update([
                    'contractor_time'    => $reschedule_request->date_time,
                    'reschedule_status'  => 0,
                ]);
            } elseif ($reschedule_request->allottee_type === Partner::TYPE_AUDITOR) {
                $booking->update([
                    'booking_time'       => $reschedule_request->date_time,
                    'reschedule_status'  => 0,
                ]);
            }

            $reschedule_request->update(['status' =>false]);

            event(new RescheduleEvent($booking, $partner_type, 'Client'));

            return new BookingResource($booking);
        }
    }

    /**
     * Decline Reschedule Request by user
     * @param Booking $booking
     * @return BookingResource
     */
    public function declineRescheduleRequest(Booking $booking)
    {
        if ($booking) {
            $reschedule_request = $booking->rescheduleRequest()->where('status', 1)->first();
            $partner_type       = $reschedule_request->allottee_type;
            $booking->update([
                'reschedule_status' => 0,
            ]);
            $reschedule_request->update(['status' =>false]);
            event(new DeclineRescheduleEvent($booking, $partner_type, 'Client'));
            return new BookingResource($booking);
        }
    }

    // Reschedule date by user here

    /**
    * Send Reschedule request to custome
    * @param Booking $booking
    * @param Request $request
    * @return \Illuminate\Http\JsonResponse
    */
    public function sendRescheduleRequestToPartner(Booking $booking, Request $request)
    {
        try {
            $this->sendRescheduleRequestValidation($request);
            $reason    = RescheduleReason::find($request->reason);
            $partner   = $booking->booking_allottee()->first()->partner; // this is partner;
            $msg       = 'Client has send you a request for reschedule the booking ' . Booking::BOOKING_UNIQUE_ID . '-' . $booking->id . ' for ' . Carbon::parse($request->date_time)->format('d M, Y H:i A') . ' schedule and reason is ' . $reason->reason ;
            $this->createRescheduleRequest($request, $reason, $booking);
            event(new PartnerRescheduleRequestEvent($booking, $partner, $msg));
            return \response()->json([
                'message' => 'Request is successfully send',
                'data'    => new BookingResource($booking),
            ], Response::HTTP_OK);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param Request $request
     */
    protected function sendRescheduleRequestValidation($request)
    {
        $request->validate([
            'reason'    => 'required',
            'date_time' => 'required| after:yesterday',
        ]);
    }

    protected function createRescheduleRequest(Request $request, $reason, $booking)
    {
        $data = [
            'allottee_id'   => $booking->user->id,
            'allottee_type' => 'Client',
            'date_time'     => $request->date_time,
            'reason'        => $reason->reason,
            'booking_id'    => $booking->id,
            'status'        => true,
        ];
        RescheduleRequest::create($data);
        $booking->update(['reschedule_status' => 1]);
    }


    public function totalAudits()
    {
        $bookings = Booking::where('status', Booking::AUDITED)->get();
        return response()->json(['data' => $bookings->count()],200);
    }
}
