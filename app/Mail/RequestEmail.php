<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
/**
 * Request email inside service.
 * Send email to request legal matter or vulnerability
 * @author Christian Arevalo
 * @version 1.0.0
 * @see  Mailable
 */
class RequestEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Request email arguments
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
        return $this
               ->subject('Request ' . $this->args['name'] . ' in Service')
               ->markdown('emails.service.request')
               ->with($this->args);
    }
}