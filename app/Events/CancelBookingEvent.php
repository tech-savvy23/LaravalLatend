<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CancelBookingEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $user;
    public  $booking_allottee;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, $bookingAllottee)
    {
        $this->user = $user;
        $this->booking_allottee = $bookingAllottee;
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
