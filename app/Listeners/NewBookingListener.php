<?php

namespace App\Listeners;

use App\Events\NewBookingEvent;
use App\Notifications\NotifyUserBookingConfirm;
use App\Notifications\User\BookingConfirmationNotification;
use Illuminate\Support\Facades\Notification;

class NewBookingListener
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
     * @param  NewBookingEvent  $event
     * @return void
     */
    public function handle(NewBookingEvent $event)
    {
        $when = now()->addMinutes(5);
        Notification::send($event->booking->user, (new NotifyUserBookingConfirm($event->booking, $event->booking->user))->delay($when));
        Notification::send($event->booking->user, (new BookingConfirmationNotification($event->booking, $event->booking->user))->delay($when));
    }


}
