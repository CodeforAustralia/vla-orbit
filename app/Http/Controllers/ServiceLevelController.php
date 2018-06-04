<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ServiceLevel;
use Auth;

/**
 * Service Level Controller.
 * Controller for the service level functionalities  
 * @author VLA & Code for Australia
 * @version 1.2.0
 * @see  Controller
 */
class ServiceLevelController extends Controller
{
    /**
     * Service level contructor. Create a new instance
     */     
    public function __construct()
    {       
        $this->middleware('auth');
    }
    /**
     * Display a listing of service level
     * @return view service level information
     */ 
    public function index()
    {
        Auth::user()->authorizeRoles('Administrator');
        return view("service_level.index");
    }
    /**
     * Display a specific service level
     * @return view single service level information page
     */
    public function show()
    {
        Auth::user()->authorizeRoles('Administrator');
        return view("service_level.show");
    }
     /**
     * Show the form for creating a new service level
     * @return view service level creation page
     */      
    public function create()
    {
        Auth::user()->authorizeRoles('Administrator');
        return view("service_level.create");
    }    
    /**
     * Store a newly or updated service level in the data base
     * @return mixed service level listing page with success/error message
     */
    public function store()
    {        
        Auth::user()->authorizeRoles('Administrator');
        $service_level_params =  array(
                                        'title'         => request('title'),
                                        'description'   => request('description'),
                                    );
        
        $service_level = new ServiceLevel();
        $response = $service_level->saveServiceLevel($service_level_params);
        
        return redirect('/service_level')->with($response['success'], $response['message']);
    }
    /**
     * Remove the specified service level from data base.
     * @param  int $sl_id service level id
     * @return mixed service level listing page with success/error message
     */
    public function destroy($sl_id)
    {
        Auth::user()->authorizeRoles('Administrator');
        $service_level = new ServiceLevel();
        $response = $service_level->deleteServiceLevel($sl_id);
        
        return redirect('/service_level')->with($response['success'], $response['message']);
    }
    /**
     * List all service level 
     * @return array list of all service level
     */
    public function list()
    {
        $service_level = new ServiceLevel();
        $result = $service_level->getAllServiceLevels();

        return array('data' => $result);
    }
}
