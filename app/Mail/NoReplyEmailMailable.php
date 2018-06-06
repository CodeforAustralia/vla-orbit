<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * No reply email service.
 *
 * @author Christian Arevalo
 * @version 1.0.0
 * @see  Mailable
 */
class NoReplyEmailMailable extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * no reply email arguments
     * @var array
     */
    public $args;

    /**
     * Create a new no reply message instance.
     *
     * @return void
     */
    public function __construct($args)
    {
        $this->args = $args;
    }

    /**
     * Build the no reply email message.
     *
     * @return $this
     */
    public function build()
    {

        $is_clc =  in_array( \App\Http\helpers::getRole(), ['CLC', 'AdminSpClc']) ;
        $attachments = $this->args['attachments'];
        $message =$this
                 ->subject( $this->args['subject'] . ' sent on : ' . date('d/m/Y h:i:s a') )
                 ->view('emails.noReplyEmail.email')->with($this->args);
        // if is CLC send message with different sender
        if ( $is_clc ) {
            $address = env('MAIL_FROM_ADDRESS_CLC', 'hello@example.com');
            $name = env('APP_NAME', 'Orbit');
            $message->from( $address, $name );
        }
        foreach ( $attachments as $index => $attachment ) {
            $message->attachData($attachment['AttachmentBytes'], $attachment['FileName']);
        }
        return $message;
    }
}