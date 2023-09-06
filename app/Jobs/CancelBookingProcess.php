<?php

namespace App\Jobs;

use App\Events\CancelBookingEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CancelBookingProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $user;
    private $booking_allottee;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $booking_allottee)
    {
        $this->user = $user;
        $this->booking_allottee =  $booking_allottee;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        event(new CancelBookingEvent($this->user, $this->booking_allottee));
    }
}
