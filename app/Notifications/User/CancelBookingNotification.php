<?php

namespace App\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CancelBookingNotification extends Notification
{
    use Queueable;

    private $message;
    private $booking_allottee;
    private $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message, $booking_allottee, $user)
    {
        $this->message = $message;
        $this->booking_allottee = $booking_allottee;
        $this->user = $user;
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
            'booking'   => $this->booking_allottee->booking->booking_service->service->name,
            'booking_id'=> $this->booking_allottee->booking->id,
            'message' => $this->message
        ];
    }
}
