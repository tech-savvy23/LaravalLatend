<?php

namespace App\Mail\Partner;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerification extends Mailable
{
    use Queueable, SerializesModels;
    public $partner;
    public $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($partner, $token)
    {
        $this->partner = $partner;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.partner.email_verification');
    }
}
