<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NotifyPartnerBookingCancelled extends Notification implements ShouldQueue
{
    use Queueable;

    private $message;
    private $booking_allottee;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message, $booking_allottee)
    {
        $this->message = $message;
        $this->booking_allottee = $booking_allottee;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if ($this->booking_allottee->partner->email_verified_at != null) {
            return ['mail'];
        }
        return [];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->line('Booking Cancelled')
            ->line($this->message);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'booking' => $this->booking_allottee->booking->booking_service->service->name,
            'booking_id' => $this->booking_allottee->booking->id,
            'message' => $this->message
        ];
    }
}
