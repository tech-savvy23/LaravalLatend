<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyUserBookingConfirm extends Notification implements ShouldQueue
{
    use Queueable;
    private $booking;
    private $user;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($booking, $user)
    {
        $this->booking = $booking;
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
        if($this->user->email_verified_at != null) {
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
        return (new MailMessage)
                    ->line('Booking is successfully created')
                    ->line('Your booking '.Booking::BOOKING_UNIQUE_ID.'-'.$this->booking->id.' has been successfully created. Please wait for auditor acceptance');
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
        ];
    }
}
