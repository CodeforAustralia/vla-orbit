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
        $view_path = '';
        switch ( $this->args['request_type'] ) {
            case 1: //'appointment_request':      'appointment_request':
                $view_path = 'emails.booking.requestEmailApptReq';
                break;

            case 2: //'for_assessment':      'for_assessment':
                $view_path = 'emails.booking.requestEmailForAssess';
                break;

            case 3: //'phone_advice':      'phone_advice':
                $view_path = 'emails.booking.requestEmailPhAdv';
                break;

            case 4: //'duty_layer':                      'duty_layer':
                $view_path = 'emails.booking.requestEmailDuttyLaw';
                break;

            case 5: //'child_support':                      'child_support':
                $view_path = 'emails.booking.requestEmailChildSupport';
                break;

            case 6: //'child_protection':                      'child_protection':
                $view_path = 'emails.booking.requestEmailChildProtection';
                break;
        }

        return $this->from( auth()->user()->email )
                    ->bcc( config('emails.booking_request_bcc') )
                    ->subject( $this->args['subject'] )
                    ->markdown($view_path)
                    ->with($this->args);
    }
}