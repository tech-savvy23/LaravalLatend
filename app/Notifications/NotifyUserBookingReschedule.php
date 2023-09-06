<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyUserBookingReschedule extends Notification implements ShouldQueue
{
    use Queueable;

    private $booking;
    private $message;
    private $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($booking, $message, $user)
    {
        $this->booking = $booking;
        $this->message = $message;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if ($this->user->email_verified_at != null) {
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

    /**
     * Get the Slack representation of the notification.
     *
     * @param mixed $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        return (new SlackMessage())
            ->content([
                'booking' => $this->booking->booking_service->service->name,
                'message' => $this->message
            ]);
    }
}
