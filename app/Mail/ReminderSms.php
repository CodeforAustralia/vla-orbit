<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
/**
 * Reminder SMS service. 
 * 
 * @author VLA & Code for Australia
 * @version 1.0.0
 * @see  Mailable
 */
class ReminderSms extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * Reminder sms arguments
     * @var array
     */
    public $args;

    /**
     * Create a new reminder message instance.
     *
     * @return void
     */
    public function __construct($args)
    {
        $this->args = $args;
    }

    /**
     * Build the reminder message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject( ' ' )->markdown('emails.reminders.sms')->with($this->args);
    }
}