<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyPartnerBookingReschedule extends Notification implements ShouldQueue
{
    use Queueable;

    private $booking;
    private $message;
    private $partner;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($booking, $message, $partner)
    {
        $this->booking = $booking;
        $this->message = $message;
        $this->partner = $partner;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if ($this->partner->email_verified_at != null) {
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
        return (new MailMessage)
            ->line('Reschedule request')
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
            'booking' => $this->booking->booking_service->service->name,
            'booking_id' => $this->booking->id,
            'message' => $this->message
        ];
    }


}
