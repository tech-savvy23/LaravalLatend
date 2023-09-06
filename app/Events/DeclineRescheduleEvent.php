<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeclineRescheduleEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $booking;
    public $partner_type;
    public $type;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($booking, $partner_type, $type)
    {
        $this->booking = $booking;
        $this->partner_type = $partner_type;
        $this->type = $type;
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
