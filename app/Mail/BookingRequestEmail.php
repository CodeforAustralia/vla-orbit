<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingRequestEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $args;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($args)
    {
        $this->args = $args;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $view_path = '';
        switch ( $this->args['request_type'] ) 
        {
            case 'appointment_request':
                $view_path = 'emails.booking.requestEmailApptReq';
                break;
            
            case 'for_assessment':
                $view_path = 'emails.booking.requestEmailForAssess';
                break;
            
            case 'phone_advice':
                $view_path = 'emails.booking.requestEmailPhAdv';
                break;

            case 'duty_layer':
                $view_path = 'emails.booking.requestEmailDuttyLaw';
                break;

            case 'child_support':
                $view_path = 'emails.booking.requestEmailChildSupport';
                break;

            case 'child_protection':
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