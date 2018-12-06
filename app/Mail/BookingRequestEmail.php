<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Booking request email service.
 *
 * @author Christian Arevalo
 * @version 1.0.0
 * @see  Mailable
 */
class BookingRequestEmail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * booking request email arguments
     * @var array
     */
    public $args;

    /**
     * Create a new booking request message instance.
     *
     * @return void
     */
    public function __construct($args)
    {
        $this->args = $args;
    }

    /**
     * Build the booking request message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from( auth()->user()->email )
                    ->bcc( config('emails.booking_request_bcc') )
                    ->subject( $this->args['subject'] )
                    ->markdown($this->args['view_path'])
                    ->with($this->args);
    }
}