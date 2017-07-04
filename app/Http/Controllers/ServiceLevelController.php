<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ServiceLevel;
use Auth;

class ServiceLevelController extends Controller
{
    public function __construct()
    {       
        $this->middleware('auth');
    }
    
    public function index()
    {
        Auth::user()->authorizeRoles('Administrator');
        return view("service_level.index");
    }

    public function show()
    {
        Auth::user()->authorizeRoles('Administrator');
        return view("service_level.show");
    }
    
    public function create()
    {
        Auth::user()->authorizeRoles('Administrator');
        return view("service_level.create");
    }    

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

    public function destroy($sl_id)
    {
        Auth::user()->authorizeRoles('Administrator');
        $service_level = new ServiceLevel();
        $response = $service_level->deleteServiceLevel($sl_id);
        
        return redirect('/service_level')->with($response['success'], $response['message']);
    }

    public function list()
    {
        $service_level = new ServiceLevel();
        $result = $service_level->getAllServiceLevels();

        return array('data' => $result);
    }
}
