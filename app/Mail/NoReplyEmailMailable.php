<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NoReplyEmailMailable extends Mailable
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
        $attachments = $this->args['attachments'];
        $message =$this->subject( $this->args['subject'] . ' sent on : ' . date('d/m/Y h:i:s a') )->view('emails.noReplyEmail.email')->with($this->args);
        foreach ($attachments as $index => $attachment) {
            $message->attachData($attachment['AttachmentBytes'], $attachment['FileName']);           
        }        
        return $message;
    }
}