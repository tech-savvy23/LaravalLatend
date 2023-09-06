<?php

namespace App\Listeners;

use App\Events\DeclineRescheduleEvent;
use App\Models\Booking;
use App\Models\Common\FireBaseMessaging;
use App\Models\Common\Otp;
use App\Notifications\NotifyDeclineRescheduleRequest;
use App\Notifications\Partner\DeclineRescheduleRequestNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class DeclineRescheduleListener
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
     * @param  DeclineRescheduleEvent  $event
     * @return void
     */
    public function handle(DeclineRescheduleEvent $event)
    {
        $when = now()->addMinutes(5);
        if ($event->type === 'Client') {
            foreach ($event->booking->booking_allottee as $booking_allotee) {
                if ($booking_allotee->allottee_type === $event->partner_type) {

                    $partner_message = 'Booking '.Booking::BOOKING_UNIQUE_ID . "-{$event->booking->id} reschedule request has been declined for ". $event->booking->booking_time->format('d M, Y H:m A') .' schedule.';
                    Notification::send($booking_allotee->partner, (new NotifyDeclineRescheduleRequest($event->booking, $partner_message, $booking_allotee->partner))->delay($when));
                    Notification::send($booking_allotee->partner, (new DeclineRescheduleRequestNotification($event->booking, $partner_message, $booking_allotee->partner)));
                    Otp::send($partner_message, $booking_allotee->partner->mobile);
                    FireBaseMessaging::sendNotification('Booking Reschedule',$partner_message, $booking_allotee->partner->partnerDevicesToken(),  $booking_allotee->partner->partnerDevices,'PARTNER');

                }

            }
        }
        if ($event->type === 'Partner') {

            $user_message = 'Booking '.Booking::BOOKING_UNIQUE_ID . "-{$event->booking->id} reschedule request has been declined for ". $event->booking->booking_time->format('d M, Y H:m A') .' schedule.';
            Notification::send($event->booking->user, (new NotifyDeclineRescheduleRequest($event->booking, $user_message, null))->delay($when));
            Notification::send($event->booking->user, (new \App\Notifications\User\DeclineRescheduleRequestNotification($event->booking, $user_message)));
            Otp::send($user_message, $event->booking->user->mobile);
            FireBaseMessaging::sendNotification('Booking Reschedule',$user_message, $event->booking->user->userDevicesToken(),  $event->booking->user->userDevices,'CLIENT');
        }

    }
}
