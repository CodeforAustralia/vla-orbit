<?php

namespace App\Mail;

use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Referral Panel Lawyer email service.
 * Email service when a referral is send with a panel lawyers information.
 * @author Christian Arevalo
 * @version 1.0.0
 * @see  Mailable
 */
class ReferralPanelLawyerEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The order instance.
     *
     * @var Order
     */
    public $order;
    /**
     * Referral panel lawyer email arguments
     * @var array
     */
    public $args;

    /**
     * Create a new referral panel lawyer message instance.
     *
     * @return void
     */
    public function __construct($args)
    {
        $this->args = $args;
    }

    /**
     * Build the referral panel lawyer message.
     *
     * @return $this
     */
    public function build()
    {
        $is_clc =  in_array( \App\Http\helpers::getRole(), ['CLC', 'AdminSpClc']) ;
        // if is CLC send message with different sender
        if ( $is_clc ) {
            $address = env('MAIL_FROM_ADDRESS_CLC', 'hello@example.com');
            $name = config('app.name');
            return $this
                   ->from( $address, $name )
                   ->subject('Referral Email')
                   ->markdown('emails.referral.panel_lawyer_email')
                   ->with($this->args);
        }
        return  $this
                ->subject('Referral Email')
                ->markdown('emails.referral.panel_lawyer_email')
                ->with($this->args);
    }
}