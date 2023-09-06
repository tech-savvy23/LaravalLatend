<?php

namespace App\Http\Controllers\Partner;

use Carbon\Carbon;
use App\Models\Media;
use App\Models\Booking;
use App\Models\Partner;
use App\Models\Common\Otp;
use Illuminate\Http\Request;
use App\Models\BookingDevice;
use App\Events\RescheduleEvent;
use App\Models\RescheduleReason;
use App\Events\StartServiceEvent;
use App\Models\RescheduleRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\LeadsResource;
use App\Models\Common\DistanceRadius;
use App\Events\DeclineRescheduleEvent;
use App\Events\RescheduleRequestEvent;
use Illuminate\Support\Facades\Storage;
use App\Models\BookingMultipleChecklist;
use App\Http\Resources\BookingDeviceResource;
use Symfony\Component\HttpFoundation\Response;
use App\Notifications\NotifyUserServiceCompleted;
use App\Notifications\NotifyPartnerServiceCompleted;

class LeadsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:partner');
        $this->partner = auth('partner')->user();
    }

    public function index(Booking $lead)
    {
        return response(new LeadsResource($lead), Response::HTTP_OK);
    }

    public function new()
    {
        if (!$this->partner->active) {
            return LeadsResource::collection([]);
        }
        $leads = Booking::with([
            'bookingBlock',
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
            ->forPartner($this->partner)
            // ->withInCity()
            ->paymentDone()
            ->NotPrevAccepted()
            ->nearBy()
            ->latest()
            ->get();

        $leads_data =  collect($leads)->filter(function ($lead) {
            if($lead->address) {
                if (DistanceRadius::distance($lead->address->latitude, $lead->address->longitude, $this->partner->latitude, $this->partner->longitude) < Booking::RADIUS) {

                    return  true;
                }
            }

        });

        $leads_data =  collect($leads_data)->filter(function ($lead) {
            if($lead->booking_space->spaceType) {
                return true;
            }
            return false;

        });

        $leads_data =  collect($leads_data)->filter(function ($lead) {
            if($lead->bookingBlock) {
                if($lead->bookingBlock->partner_id != $this->partner->id) {
                    return true;
                }
                return false;
            }
            return true;

        });

        return LeadsResource::collection($leads_data);
    }

    public function accepted()
    {
        $booking_status = $this->partner->type == Partner::TYPE_AUDITOR ? Booking::AUDITOR_ACCEPTED : Booking::CONTRACTOR_ACCEPTED;
        $leads = Booking::with(['address',
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
            ->where('status', $booking_status)
            ->whereHas('booking_allottee', function ($q) {
                return $q->where('status', 1)
                    ->where('allottee_type', auth('partner')->user()->type)
                    ->where('allottee_id', auth('partner')->id());
            })
            ->orderByDesc('updated_at')->get();
        return LeadsResource::collection($leads);
    }

    public function cancelled()
    {
        $leads = Booking::with(['address',
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
            ->where('status', 0)
            ->whereHas('booking_allottee', function ($q) {
                return $q->where('status', 0)
                    ->where('allottee_type', auth('partner')->user()->type)
                    ->where('allottee_id', auth('partner')->id());
            })->latest()->paginate(10);
        return LeadsResource::collection($leads);
    }

    public function completed()
    {
        $booking_status = $this->partner->type == Partner::TYPE_AUDITOR ? Booking::AUDITED : Booking::COMPLETED;
        $leads = Booking::with(['address',
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
            ->where('status', '>=', $booking_status)
            ->whereHas('booking_allottee', function ($q) {
                return $q->where('status', 1)
                    ->where('allottee_type', auth('partner')->user()->type)
                    ->where('allottee_id', auth('partner')->id());
            })
            ->latest()->paginate(10);
        return LeadsResource::collection($leads);
    }

    public function accept(Booking $lead)
    {
        if (!$this->partner->active) {
            return response()->json([
                'error' => 'Sorry! Your account is Inactive, please contact admin.',
            ], Response::HTTP_NOT_FOUND);
        }

        if ($lead->status == 0 || $lead->status == 3) {
            $lead->accept();
            return response(null, Response::HTTP_ACCEPTED);
        }
        return response()->json([
            'error' => 'Sorry! Lead is already accepted.',
        ], Response::HTTP_NOT_FOUND);
    }

    public function cancel(Booking $lead)
    {
        $lead->cancel();
        return response()->json('cancelled', Response::HTTP_OK);
    }

    public function submit(Booking $lead)
    {
        $booking_status = $this->partner->type == Partner::TYPE_AUDITOR ? Booking::AUDITED : Booking::COMPLETED;
        $lead->update(['status' => $booking_status, 'reschedule_status' => 1]);
        $lead->user->notify(new NotifyUserServiceCompleted());
        $this->partner->notify(new NotifyPartnerServiceCompleted());
    }

    public function codSubmit(Booking $lead)
    {
        $booking_status = $this->partner->type == Partner::TYPE_AUDITOR ? Booking::AUDITED : Booking::COMPLETED;
        $lead->update(['status' => $booking_status]);
        $lead->payment()->latest()->update(['status' => 1]);
        $lead->user->notify(new NotifyUserServiceCompleted());
        $this->partner->notify(new NotifyPartnerServiceCompleted());
    }

    public function verifyOTP(Booking $lead)
    {
        // Fetch otp by user
        $db_otp = Otp::where([
            'model_id' => $lead->user->id,
            'model_type' => get_class($lead->user),
            'for_type' => get_class($lead),
            'for_id' => $lead->id,
        ])->first();

        $user_otp = request('otp');

        if ($db_otp->otp == $user_otp) {
            $lead->update(['otp_status' => true]);
            $db_otp->delete();
            event(new StartServiceEvent($this, $lead->user));
//            $lead->user->notify(new NotifyUserServiceStarted($this, $this->partner));
//            $this->partner->notify(new NotifyPartnerServiceStarted($this));
            return response(null, Response::HTTP_ACCEPTED);
        }
        return response('Invalid OTP, please try again.', Response::HTTP_NOT_FOUND);
    }

    /**
     * Reschedule send,approve and decline here
     *
     */

    /**
     * Send Reschedule request to custome
     * @param Booking $booking
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendRescheduleRequest(Booking $booking, Request $request)
    {
        try {
            $this->sendRescheduleRequestValidation($request);

            $reason = RescheduleReason::find($request->reason);
            $user = $booking->user; // this is customer;
            $msg = 'Auditor has send you a request for reschedule the booking ' . Booking::BOOKING_UNIQUE_ID . '-' . $booking->id . ' for ' . Carbon::parse($request->date_time)->format('d M, Y H:i A') . ' schedule and reason is ' . $reason->reason;
            $this->createRescheduleRequest($request, $reason, $booking);
            event(new RescheduleRequestEvent($booking, $user, $msg));
            return \response()->json([
                'message' => 'Request is successfully send',
                'data' => new LeadsResource($booking),
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
            'reason' => 'required',
            'date_time' => 'required| after:yesterday',
        ]);
    }

    protected function createRescheduleRequest(Request $request, $reason, $booking)
    {
        $data = [
            'allottee_id' => $this->partner->id,
            'allottee_type' => $this->partner->type,
            'date_time' => $request->date_time,
            'reason' => $reason->reason,
            'booking_id' => $booking->id,
            'status' => true,
        ];
        RescheduleRequest::create($data);
        $booking->update(['reschedule_status' => 1]);
    }

    /**
     * Accept Reschedule Request by partner
     * @param Booking $booking
     * @return LeadsResource
     */
    public function approveRescheduleRequest(Booking $booking)
    {
        if ($booking) {
            $reschedule_request = $booking->rescheduleRequest->where('status', 1)->first();

            $partner_type = $reschedule_request->allottee_type;

            if ($booking->status === Booking::CONTRACTOR_ACCEPTED) {
                $booking->update([
                    'contractor_time' => $reschedule_request->date_time,
                    'reschedule_status' => 0,
                ]);
            } elseif ($booking->status === Booking::AUDITOR_ACCEPTED) {
                $booking->update([
                    'booking_time' => $reschedule_request->date_time,
                    'reschedule_status' => 0,
                ]);
            }

            $reschedule_request->update(['status' => false]);

            event(new RescheduleEvent($booking, $partner_type, 'Partner'));

            return new LeadsResource($booking);
        }
    }

    /**
     * Decline Reschedule Request by partner
     *
     * @param Booking $booking
     * @return LeadsResource
     */
    public function declineRescheduleRequest(Booking $booking)
    {
        if ($booking) {
            $reschedule_request = $booking->rescheduleRequest->where('status', 1)->first();
            $partner_type = $reschedule_request->allottee_type;
            $booking->update([
                'reschedule_status' => 0,
            ]);
            $reschedule_request->update(['status' => false]);
            event(new DeclineRescheduleEvent($booking, $partner_type, 'Partner'));
            return new LeadsResource($booking);
        }
    }

    //Booking device and multiple checklist here

    /**
     * Get booking multiple checklist type
     *
     * @return BookingMultipleChecklist
     */
    public function getMultipleChecklist($id, $checklistId)
    {
        return response()->json([
            'data' => BookingMultipleChecklist::where('booking_id', $id)->where('checklist_id', $checklistId)->get(),
        ], 200);
    }

    /**
     * Storing booking multiple checklist
     *
     * @return BookingMultipleChecklist
     */
    public function storeMultipleChecklist()
    {
        return BookingMultipleChecklist::store();
    }

    /**
     * Destory booking multiple checklist type
     *
     * @return BookingMultipleChecklist
     */
    public function destoryMultipleChecklist($id)
    {
        $booking_multiple_checklist = BookingMultipleChecklist::find($id);

        $booking_multiple_checklist->bookingReports()->each(function ($booking_report) {
            // Delete all report media file from cloud
            $booking_report->media()->each(function ($media) {
                Storage::disk(env('DISK', 'public'))->delete("images/{$media->name}");
            });

            // Delete all report media file from database
            $booking_report->media()->delete();

            // Delete all report file from database
            $booking_report->delete();
        });
        // Delete multiple check list file from cloud
        $booking_multiple_checklist->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    /**
     * Update booking multiple checklist type
     *
     * @return BookingMultipleChecklist
     */
    public function updateMultipleChecklist($id)
    {
        $booking_multiple_checklist = BookingMultipleChecklist::find($id);

        $booking_multiple_checklist->update(['title' => request()->title]);

        return response()->json([
            'data' => $booking_multiple_checklist,
        ], 200);
    }

    /**
     * Store multiple booking device
     *
     * @param Request
     *
     * @return BookingDevice
     */
    public function storeBookingDevice()
    {
        $this->validate(request(), [
            'booking_id' => 'required',
            'checklist_type_id' => 'required',
            'title' => 'required',
            'value' => 'required',
            'checklist_id' => 'required',
        ]);
        return new BookingDeviceResource(BookingDevice::store());
    }

    /**
     * Destory booking multiple checklist type
     *
     * @return BookingDevice
     */
    public function destoryBookingDevice($id)
    {
        BookingDevice::find($id)->delete();
        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    /**
     * Update booking device
     *
     * @return BookingMultipleChecklist
     */
    public function updateBookingDevice($id)
    {
        $booking_device = BookingDevice::find($id);

        $booking_device->update(request()->only('title', 'value', 'result'));

        return response()->json([
            'data' => $booking_device,
        ], 200);
    }

    /**
     * Get all booking devices
     *
     * @param $id
     *
     * @return JSON
     */
    public function getBookingDevice($id, $checklistTypeId)
    {
        return response()->json([
            'data' => BookingDeviceResource::collection(BookingDevice::where('booking_id', $id)->where('checklist_type_id', $checklistTypeId)->get()),
        ], 200);
    }

    /**
     * Add booking device image
     *
     * @param id
     *
     * @return JSON
     */
    public function addBookingDeviceImage($bookingDeviceId)
    {
        $device = BookingDevice::find($bookingDeviceId);
        request()->validate([
            'image' => 'required',
        ]);
        try {
            $image = $device->imageUpload(request('image'));
            return response($image, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response('Image can not upload ' . $e->getMessage(), HttpResponse::HTTP_NOT_ACCEPTABLE);
        }
    }

    /**
     * Delete booking device image
     *
     * @param id
     *
     * @return JSON
     */
    public function deleteBookingDeviceImage($mediaId)
    {
        $image = Media::find($mediaId);
        Storage::disk(env('DISK'))->delete("images/{$image->name}");
        $image->delete();
        return response('deleted', Response::HTTP_NO_CONTENT);
    }
}
