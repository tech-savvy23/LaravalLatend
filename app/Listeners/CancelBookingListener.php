<?php

namespace App\Listeners;

use App\Events\CancelBookingEvent;
use App\Models\Booking;
use App\Models\Common\FireBaseMessaging;
use App\Models\Common\Otp;
use App\Notifications\NotifyPartnerBookingCancelled;
use App\Notifications\NotifyUserBookingCancelled;
use App\Notifications\User\CancelBookingNotification;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CancelBookingListener
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
     * @param  CancelBookingEvent  $event
     * @return void
     */
    public function handle(CancelBookingEvent $event)
    {
        $when = now()->addMinutes(5);
        $booking_allottee = $event->booking_allottee;

         //send sms to user
        $user_message = trans('message.booking.cancelled.receiver', ['booking' => Booking::BOOKING_UNIQUE_ID . "-{$booking_allottee->booking->id}", 'name' => $booking_allottee->partner->name.' ('.$booking_allottee->allottee_type.')']);
        Otp::send($user_message, $event->user->mobile);
        // notify user
        $event->user->notify((new NotifyUserBookingCancelled($user_message, $booking_allottee, $event->user))->delay($when));
        $event->user->notify((new CancelBookingNotification($user_message, $booking_allottee, $event->user)));
        FireBaseMessaging::sendNotification(trans('message.booking.cancelled.title'),$user_message, $event->user->userDevicesToken(),  $event->user->userDevices,'CLIENT');

        // Notify Partner
        $partner_message = trans('message.booking.cancelled.sender', ['booking' => Booking::BOOKING_UNIQUE_ID . "-{$booking_allottee->booking->id}"]);
        $booking_allottee->partner->notify((new NotifyPartnerBookingCancelled($partner_message, $booking_allottee))->delay($when));
        $booking_allottee->partner->notify((new  \App\Notifications\Partner\CancelBookingNotification($partner_message, $booking_allottee)));
        FireBaseMessaging::sendNotification(trans('message.booking.cancelled.title'),$partner_message,$booking_allottee->partner->partnerDevicesToken(),$booking_allottee->partner->partnerDevices,'PARTNER');
    }
}
