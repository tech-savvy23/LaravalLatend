<?php

namespace App\Listeners;

use App\Events\PaymentEvent;
use App\Mail\SendFile;
use App\Models\Booking;
use App\Models\Common\FireBaseMessaging;
use App\Notifications\PaymentNotification;
use App\Notifications\User\PaymentsNotification;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class PaymentListener
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
     * @param  PaymentEvent  $event
     * @return void
     */
    public function handle(PaymentEvent $event)
    {
        $when = now()->addMinutes(5);
        //Client
        $user_message = 'Booking '.Booking::BOOKING_UNIQUE_ID . "-{$event->booking->id}". ' payment has been successfully done.';
        $event->booking->user->notify((new PaymentNotification($event->booking, $user_message, null))->delay($when));
        $event->booking->user->notify((new PaymentsNotification($event->booking, $user_message)));
        FireBaseMessaging::sendNotification('Payment Done', $user_message, $event->booking->user->userDevicesToken(), $event->booking->user->userDevices, 'CLIENT');
        //Partner
        foreach ($event->booking->booking_allottee as $booking_allotee) {
            $booking_allotee->partner->notify((new PaymentNotification($event->booking, $user_message, $booking_allotee->partner))->delay($when));
            $booking_allotee->partner->notify((new \App\Notifications\Partner\PaymentsNotification($event->booking, $user_message)));
            FireBaseMessaging::sendNotification('Payment Done', $user_message, $booking_allotee->partner->partnerDevicesToken(), $booking_allotee->partner->partnerDevices, 'PARTNER');
        }
    }
}
