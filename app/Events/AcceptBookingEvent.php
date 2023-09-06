<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AcceptBookingEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $booking_allottee;
    public $otp;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($booking_allottee, $otp)
    {
        $this->booking_allottee = $booking_allottee;
        $this->otp = $otp;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
