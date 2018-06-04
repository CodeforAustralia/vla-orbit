<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Booking email service.
 * 
 * @author VLA & Code for Australia
 * @version 1.0.0
 * @see  Mailable
 */
class BookingEmail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * booking email arguments
     * @var array
     */
    public $args;

    /**
     * Create a new booking message instance.
     *
     * @return void
     */
    public function __construct($args)
    {
        $this->args = $args;
    }

    /**
     * Build the booking message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject( $this->args['subject'] )->markdown('emails.booking.email');
    }
}