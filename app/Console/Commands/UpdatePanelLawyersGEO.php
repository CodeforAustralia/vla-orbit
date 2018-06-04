<?php

namespace App\Console\Commands;
use App\Http\Controllers\PanelLawyersController;
use Illuminate\Console\Command;
/**
 * Panel Lawyers updateservice.
 * 
 * @author VLA & Code for Australia
 * @version 1.0.0
 * @see  Command
 */
class UpdatePanelLawyersGEO extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'panelLawyers:updateGEO';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the Panel Lawyers geographical information';

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
        $panelLawyersController = new PanelLawyersController();
        $panelLawyersController-> storeLatLng();
    }
}
