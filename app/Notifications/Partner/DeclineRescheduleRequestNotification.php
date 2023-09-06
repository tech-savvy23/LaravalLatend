<?php

namespace App\Notifications\Partner;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeclineRescheduleRequestNotification extends Notification
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
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
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
            'message'   => $this->message
        ];
    }
}
