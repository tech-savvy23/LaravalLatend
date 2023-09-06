<?php

namespace App\Listeners;

use App\Events\RescheduleRequestEvent;
use App\Models\Common\FireBaseMessaging;
use App\Models\Common\Otp;
use App\Notifications\NotifyUserBookingReschedule;
use App\Notifications\User\BookingRescheduleNotification;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class RescheduleRequestListener
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
     * @param  RescheduleRequestEvent  $event
     * @return void
     */
    public function handle(RescheduleRequestEvent $event)
    {
        $when = now()->addMinutes(5);
        Notification::send($event->user, (new NotifyUserBookingReschedule($event->booking, $event->message, $event->user))->delay($when));
        Notification::send($event->user, (new BookingRescheduleNotification($event->booking, $event->message)));
        Otp::send($event->message, $event->user->mobile);
        FireBaseMessaging::sendNotification('Reschedule Request',$event->message, $event->user->userDevicesToken(),  $event->user->userDevices,'CLIENT');
    }
}
