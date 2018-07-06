<?php

namespace App\Mail;

use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
/**
 * Referral email service.
 *
 * @author Christian Arevalo
 * @version 1.0.0
 * @see  Mailable
 */
class ReferralEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The order instance.
     *
     * @var Order
     */
    public $order;
    /**
     * referral arguments
     * @var array
     */
    public $args;

    /**
     * Create a new referral message instance.
     *
     * @return void
     */
    public function __construct($args)
    {
        $this->args = $args;
    }

    /**
     * Build the referral message.
     *
     * @return $this
     */
    public function build()
    {
        $is_clc =  in_array( \App\Http\helpers::getRole(), ['CLC', 'AdminSpClc']) ;
        // if is CLC send message with different sender
        if( $is_clc ) {
            $address = env('MAIL_FROM_ADDRESS_CLC', 'hello@example.com');
            $name = config('app.name');
            return $this
                   ->from( $address, $name )
                   ->markdown('emails.referral.email')
                   ->with($this->args);
        }
        return $this->markdown('emails.referral.email')->with($this->args);
    }
}