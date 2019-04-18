<?php

namespace App\Console\Commands;

use App\NoReplyEmail;
use Illuminate\Console\Command;

class UpdateNameOnNRE extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nre:update_name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the name in No reply emails';

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

        $no_reply_email = new NoReplyEmail();
        $page_counter = 0;
        for ($i=0; $i <100 ; $i++) {
            $start = microtime(true);
            $args_page = [
                'PerPage' 		=> 1000,
                'Page' 			=> $page_counter,
                'SortColumn' 	=> 'VML_REF_NO',
                'SortOrder' 	=> 'ASC' ,
                'Search' 		=> '',
                'ColumnSearch' 	=> '',
            ];
            $page_counter ++;
            $last_page=$no_reply_email->updateName($args_page);
            $time  = microtime(true) - $start;
            $this->info('Time with the round #' . $i .' of ' . $last_page . " " . $time);
            if ( $last_page==$page_counter){
                break;
            }
        }


        $this->info('Update Finish');
    }
}
