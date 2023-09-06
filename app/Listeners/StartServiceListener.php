<?php

namespace App\Listeners;

use App\Events\StartServiceEvent;
use App\Notifications\NotifyPartnerServiceStarted;
use App\Notifications\NotifyUserServiceStarted;
use App\Notifications\User\StartServiceNotification;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StartServiceListener
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
     * @param  StartServiceEvent  $event
     * @return void
     */
    public function handle(StartServiceEvent $event)
    {
        $when = now()->addMinutes(5);
        $event->user->notify((new NotifyUserServiceStarted($event->booking, $event->booking->partner, $event->user)));
        $event->booking->partner->notify(new NotifyPartnerServiceStarted($event->booking));
    }
}
