<?php

namespace App\Providers;

use App\Listeners\RescheduleRequest;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class
        ],
        'App\Events\RescheduleRequestEvent' => [

            'App\Listeners\RescheduleRequestListener'
        ],

        'App\Events\PartnerRescheduleRequestEvent' => [

            'App\Listeners\PartnerRescheduleRequestListener'
        ],
        'App\Events\NewBookingEvent' => [

            'App\Listeners\NewBookingListener'
        ],
         'App\Events\RescheduleEvent' => [
            'App\Listeners\RescheduleListener'
        ],
        'App\Events\NewBookingEvent' => [
            'App\Listeners\NewBookingListener'
        ],
        'App\Events\CancelBookingEvent' => [
            'App\Listeners\CancelBookingListener'
        ],
        'App\Events\AcceptBookingEvent' => [
            'App\Listeners\AcceptBookingListener'
        ],
        'App\Events\StartServiceEvent' => [
            'App\Listeners\StartServiceListener'
        ],
          'App\Events\PaymentEvent' => [
            'App\Listeners\PaymentListener'
        ],
        'App\Events\DeclineRescheduleEvent' => [
            'App\Listeners\DeclineRescheduleListener'
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
