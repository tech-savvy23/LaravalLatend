<?php

namespace App\Listeners;

use App\Events\RescheduleEvent;
use App\Models\Booking;
use App\Models\Common\FireBaseMessaging;
use App\Models\Common\Otp;
use App\Notifications\NotifyRescheduleBookingSuccess;
use App\Notifications\User\BookingRescheduleSuccessNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;

class RescheduleListener
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
     * @param  RescheduleEvent  $event
     * @return void
     */
    public function handle(RescheduleEvent $event)
    {
        $when = now()->addMinutes(5);
        if ($event->type === 'Client') {

            $user_message = 'Booking '.Booking::BOOKING_UNIQUE_ID . "-{$event->booking->id} is successfully reschedule";

            Notification::send ($event->booking->user,(new NotifyRescheduleBookingSuccess($event->booking, $user_message, null))->delay($when));
            Notification::send ($event->booking->user,(new BookingRescheduleSuccessNotification($event->booking, $user_message)));

            Otp::send($user_message, $event->booking->user->mobile);

            FireBaseMessaging::sendNotification('Booking Reschedule',$user_message, $event->booking->user->userDevicesToken(),  $event->booking->user->userDevices,'CLIENT');

            foreach ($event->booking->booking_allottee as $booking_allotee) {

                if ($booking_allotee->allottee_type === $event->partner_type) {

                    $partner_message = 'Booking '.Booking::BOOKING_UNIQUE_ID . "-{$event->booking->id} has been rescheduled on ". $event->booking->booking_time->format('d M, Y H:m A');

                    Notification::send ($booking_allotee->partner, (new NotifyRescheduleBookingSuccess($event->booking, $partner_message, $booking_allotee->partner))->delay($when));
                    Notification::send ($booking_allotee->partner, (new \App\Notifications\Partner\BookingRescheduleSuccessNotification($event->booking, $partner_message)));

                    Otp::send($partner_message, $booking_allotee->partner->mobile);

                    FireBaseMessaging::sendNotification('Booking Reschedule',$partner_message, $booking_allotee->partner->partnerDevicesToken(),  $booking_allotee->partner->partnerDevices,'PARTNER');

                }

            }
        }

        if ($event->type === 'Partner') {

            //Partner
            $partner_message = 'Booking '.Booking::BOOKING_UNIQUE_ID . "-{$event->booking->id} is successfully reschedule";

            Notification::send (auth('partner')->user(),(new NotifyRescheduleBookingSuccess($event->booking, $partner_message, auth('partner')->user()))->delay($when));
            Notification::send (auth('partner')->user(),(new \App\Notifications\Partner\BookingRescheduleSuccessNotification($event->booking, $partner_message)));

            Otp::send($partner_message, auth('partner')->user()->mobile);

            FireBaseMessaging::sendNotification('Booking Reschedule',$partner_message, auth('partner')->user()->partnerDevicesToken(), auth('partner')->user()->partnerDevices, 'PARTNER');


            //User
            $user_message = 'Booking '.Booking::BOOKING_UNIQUE_ID . "-{$event->booking->id} has been rescheduled on ". $event->booking->booking_time->format('d M, Y H:m A');

            Notification::send ($event->booking->user, (new NotifyRescheduleBookingSuccess($event->booking, $user_message, null))->delay($when));
            Notification::send ($event->booking->user, (new BookingRescheduleSuccessNotification($event->booking, $user_message)));

            Otp::send($user_message, $event->booking->user->mobile);

            FireBaseMessaging::sendNotification('Booking Reschedule',$user_message, $event->booking->user->userDevicesToken(), $event->booking->user->userDevices, 'CLIENT');
        }


    }
}
