<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PartnerRescheduleRequestEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $booking;
    public $partner;
    public $message;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($booking, $partner, $message)
    {
        $this->booking = $booking;
        $this->partner = $partner;
        $this->message = $message;
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
