<?php

namespace App\Mail;

use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReferralEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The order instance.
     *
     * @var Order
     */
    public $order;

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
        $is_clc =  in_array( \App\Http\helpers::getRole(), ['CLC', 'AdminSpClc']) ;
        if( $is_clc )
        {
            $address = env('MAIL_FROM_ADDRESS_CLC', 'hello@example.com');
            $name = env('APP_NAME', 'Orbit');
            return $this->from( $address, $name )->markdown('emails.referral.email')->with($this->args);
        }
        return $this->markdown('emails.referral.email')->with($this->args);
    }
}