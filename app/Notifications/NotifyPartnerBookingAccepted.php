<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NotifyPartnerBookingAccepted extends Notification implements ShouldQueue
{
    use Queueable;

    protected $title;
    protected $body;
    protected $booking;
    protected $bookingAllottee;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($title, $body,$booking, $bookingAllottee)
    {
        $this->title     = $title;
        $this->body = $body;
        $this->booking = $booking;
        $this->bookingAllottee = $bookingAllottee;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if ($this->bookingAllottee->partner->email_verified_at != null) {
            return ['mail'];

        }
        return [];

    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
                    ->line($this->title)
                    ->line($this->body);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'booking'   => $this->booking->booking_service->service->name,
            'booking_id'=> $this->booking->id,
            'message'   => $this->body
        ];
    }
}
