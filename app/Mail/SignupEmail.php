<?php

namespace App\Mail;

use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
/**
 * Signup email service.
 * Send email to request a new user
 * @author Sebastian Currea
 * @version 1.0.0
 * @see  Mailable
 */
class SignupEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The order instance.
     *
     * @var Order
     */
    public $order;
    /**
     * Signup email arguments
     * @var array
     */
    public $args;

    /**
     * Create a new signup request message instance.
     *
     * @return void
     */
    public function __construct($args)
    {
        $this->args = $args;
    }

    /**
     * Build the signup request message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = isset( $this->args['subject'] ) ? $this->args['subject'] : 'Request login';
        return $this
                    ->subject($subject)
                    ->markdown('emails.login.signup')
                    ->with($this->args);
    }
}