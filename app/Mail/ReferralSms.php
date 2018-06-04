<?php

namespace App\Mail;

use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
/**
 * Referral SMS service. 
 * 
 * @author VLA & Code for Australia
 * @version 1.0.0
 * @see  Mailable
 */
class ReferralSms extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The order instance.
     *
     * @var Order
     */
    public $order;
    /**
     * Referral sms arguments
     * @var array
     */
    public $args;

    /**
     * Create a new referral sms message instance.
     *
     * @return void
     */
    public function __construct($args)
    {
        $this->args = $args;
    }

    /**
     * Build the referral sms message.
     *
     * @return $this
     */
    public function build()
    {
        $is_clc =  in_array( \App\Http\helpers::getRole(), ['CLC', 'AdminSpClc']) ;
        // if is CLC send message with different sender
        if( $is_clc )
        {
            $address = env('MAIL_FROM_ADDRESS_CLC', 'hello@example.com');
            $name = env('APP_NAME', 'Orbit');
            return $this->from( $address, $name )->subject(' ')->markdown('emails.referral.sms')->with($this->args);
        }
        return $this->subject(' ')->markdown('emails.referral.sms')->with($this->args);
    }
}