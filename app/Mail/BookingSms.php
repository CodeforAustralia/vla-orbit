<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Booking SMS service.
 *
 * @author Christian Arevalo
 * @version 1.0.0
 * @see  Mailable
 */
class BookingSms extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * booking SMS arguments
     * @var array
     */
    public $args;

    /**
     * Create a new booking sms instance.
     *
     * @return void
     */
    public function __construct($args)
    {
        $this->args = $args;
    }

    /**
     * Build the booking sms message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject( ' ' )->markdown('emails.booking.sms')->with($this->args);
    }
}