<?php

namespace App\Listeners;

use App\Events\PartnerRescheduleRequestEvent;
use App\Models\Common\FireBaseMessaging;
use App\Models\Common\Otp;
use App\Notifications\NotifyPartnerBookingReschedule;
use App\Notifications\NotifyUserBookingReschedule;
use App\Notifications\Partner\BookingRescheduleNotification;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class PartnerRescheduleRequestListener
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
     * @param RescheduleRequestEvent $event
     * @return void
     */
    public function handle(PartnerRescheduleRequestEvent $event)
    {
        $when = now()->addMinutes(5);
        Notification::send($event->partner, (new NotifyPartnerBookingReschedule($event->booking, $event->message, $event->partner))->delay($when));
        Notification::send($event->partner, (new BookingRescheduleNotification($event->booking, $event->message, $event->partner)));
        Otp::send($event->message, $event->partner->mobile);
        FireBaseMessaging::sendNotification('Reschedule Request', $event->message, $event->partner->partnerDevicesToken(), $event->partner->partnerDevices, 'PARTNER');
    }
}
