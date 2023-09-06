<?php

namespace App\Http\Controllers\Partner\Auth;

use App\Models\Booking;
use App\Models\Partner;
use App\Models\BookingAllottee;
use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Notifications\NotifyUserPartnerIsBlocked;
use App\Notifications\NotifyPartnerPartnerIsBlocked;

// use Bitfumes\ApiAuth\Helpers\ImageCrop;

class StatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function block(Partner $partner)
    {
        $partner->update(['active' => false]);
        $allottee = BookingAllottee::where(['allottee_id' => $partner->id, 'allottee_type' => $partner->type])->get();
        $allottee->each(function ($model) {
            if ($model->booking->status == Booking::AUDITOR_ACCEPTED || $model->booking->status < Booking::AUDITED) {
                $model->booking->update(['status'=> 0]);
                $model->booking->user->notify(new NotifyUserPartnerIsBlocked());
                $model->booking->reports->each->delete();
                $model->delete();
            }
        });
        $partner->notify(new NotifyPartnerPartnerIsBlocked());
        return response('blocked', Response::HTTP_ACCEPTED);
    }

    public function unblock(Partner $partner)
    {
        $partner->update(['active' => true]);
        return response('unblocked', Response::HTTP_ACCEPTED);
    }

    /**
     * @param Partner $partner
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function policeVerification(Partner $partner)
    {
        $partner->update(['police_verified_at' => Carbon::now()]);
        return response(Partner::find($partner->id), Response::HTTP_ACCEPTED);

    }
}
