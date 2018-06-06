<?php

namespace App\Console\Commands;


use App\SentSms;
use Illuminate\Console\Command;
/**
 * Reminders email service.
 *
 * @author Christian Arevalo
 * @version 1.0.0
 * @see  Command
 */
class SendReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets all bookings and templates to be send by SMS';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $sent_sms = new SentSms();
        $sent_sms->sendMessages();

    }
}
