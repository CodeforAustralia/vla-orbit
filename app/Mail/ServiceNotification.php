<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Service Notification for out of date services.
 *
 * @author Sebastian Currea
 * @version 1.0.0
 * @see  Mailable
 */
class ServiceNotification extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * arguments
     * @var array
     */
    public $args;

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct($args)
    {
        $this->args = $args;
    }

    /**
     * Build the notification message.
     *
     * @return $this
     */
    public function build()
    {
        $message = $this
                ->subject( $this->args['subject'] )
                ->view('emails.service.update_notification')->with($this->args);
        // if is CLC send message with different sender
        if($this->args['service_provider_type'] == 'CLC') {
            $address = env('MAIL_FROM_ADDRESS_CLC', 'hello@example.com');
            $name = config('app.name');
            $message->from( $address, $name );
        }
        return $message;
    }
}