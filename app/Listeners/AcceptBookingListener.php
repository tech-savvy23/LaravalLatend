<?php

namespace App\Listeners;

use App\Events\AcceptBookingEvent;
use App\Jobs\AcceptBookingProcess;
use App\Models\Booking;
use App\Models\Common\FireBaseMessaging;
use App\Models\Common\Otp;
use App\Notifications\NotifyPartnerBookingAccepted;
use App\Notifications\NotifyUserBookingAccepted;
use App\Notifications\User\AcceptBookingNotification;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AcceptBookingListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AcceptBookingEvent  $event
     * @return void
     */
    public function handle(AcceptBookingEvent $event)
    {
        $when = now()->addMinutes(5);
        $msg   = $event->booking_allottee->partner->name." has accepted your ".Booking::BOOKING_UNIQUE_ID."-".$event->booking_allottee->booking->id." booking, please show this OTP: $event->otp to auditor when he visit your place or you can find this otp at booking section on mobile app.";
        $booking_date = Carbon::parse($event->booking_allottee->booking->booking_time)->format('d M, Y h:i a');
        $user_message_title = 'Booking is accepted.';
        $user_message_body = $event->booking_allottee->partner->name.' has accepted your booking '.Booking::BOOKING_UNIQUE_ID.'-'.$event->booking_allottee->booking->id.' on '.$booking_date;

        Otp::send($msg, $event->booking_allottee->booking->user->mobile);
        $event->booking_allottee->booking->user->notify((new NotifyUserBookingAccepted($user_message_title, $user_message_body,$event->booking_allottee->booking))->delay($when));
        $event->booking_allottee->booking->user->notify((new AcceptBookingNotification($user_message_title, $user_message_body,$event->booking_allottee->booking)));
        FireBaseMessaging::sendNotification(trans('message.booking.accepted.title'),trans('message.booking.accepted.receiver',['booking' => Booking::BOOKING_UNIQUE_ID . "-{$event->booking_allottee->booking->id}",'name' => $event->booking_allottee->partner->name]), $event->booking_allottee->booking->user->userDevicesToken(),  $event->booking_allottee->booking->user->userDevices,'CLIENT');

        $partner_message_title ='You have accepted new lead.';
        $partner_message_body = 'You have successfully accepted a booking '.Booking::BOOKING_UNIQUE_ID.'-'.$event->booking_allottee->booking->id.' on '.$booking_date.', please ask for OTP from customer before starting your service.';
        $event->booking_allottee->partner->notify((new NotifyPartnerBookingAccepted($partner_message_title,$partner_message_body,$event->booking_allottee->booking, $event->booking_allottee))->delay($when));
        $event->booking_allottee->partner->notify((new \App\Notifications\Partner\AcceptBookingNotification($partner_message_title,$partner_message_body,$event->booking_allottee->booking, $event->booking_allottee)));

    }
}
