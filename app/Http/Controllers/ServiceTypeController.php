<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ServiceType;
use Auth;

class ServiceTypeController extends Controller
{    
    public function __construct()
    {       
        $this->middleware('auth');
    }
    
    public function index()
    {
        Auth::user()->authorizeRoles('Administrator');
        return view("service_type.index");
    }

    public function show()
    {
        Auth::user()->authorizeRoles('Administrator');
        return view("service_type.show");
    }
    
    public function create()
    {
        Auth::user()->authorizeRoles('Administrator');
        return view("service_type.create");
    }

    public function store()
    {        
        Auth::user()->authorizeRoles('Administrator');
        $service_type_params =  array(
                                        'title'         => request('title'),
                                        'description'   => request('description'),
                                    );
        
        $service_type = new ServiceType();
        $response = $service_type->saveServiceType($service_type_params);
        
        return redirect('/service_type')->with($response['success'], $response['message']);
    }

    public function destroy($st_id)
    {
        Auth::user()->authorizeRoles('Administrator');
        $service_type = new ServiceType();
        $response = $service_type->deleteServiceType($st_id);
        
        return redirect('/service_type')->with($response['success'], $response['message']);
    }
    
    public function list()
    {
        $service_type = new ServiceType();
        $result = $service_type->getAllServiceTypes();

        return array('data' => $result);
    }
}
