<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service;
use App\ServiceType;
use App\ServiceLevel;
use App\ServiceProvider;

class ServiceController extends Controller
{    
    public function index()
    {
        return view("service.index");
    }

    public function show()
    {
        return view("service.show");
    }
    

    public function store()
    {                

        $sv_params = array(                    
                            'ServiceName'   => request('name'),
                            'Phone'         => request('phone'),
                            'Email'         => request('email'),
                            'Description'   => request('description'),
                            'ServiceProviderId'     => request('service_provider_id'), 
                            'Wait'           => request('wait'),
                            'LocationId'     => request('location_id'), //This is service Level not location
                            'ServiceTypeId'  => request('service_type_id'), 
                            );
        $service = new Service();

        $response = $service->saveService( $sv_params );
        
        return redirect('/service')->with( $response['success'], $response['message'] );
    }
    
    public function create()
    {
        $service_type_obj = new ServiceType();
        $service_types    = $service_type_obj->getAllServiceTypes();

        $service_level_obj  = new ServiceLevel();
        $service_levels     = $service_level_obj->getAllServiceLevels();

        $service_provider_obj   = new ServiceProvider();
        $service_providers      = $service_provider_obj->getAllServiceproviders();

        return view( "service.create", compact( 'service_types','service_levels','service_providers' ) );
    }

    public function destroy($sv_id)
    {
        $service  = new Service();
        $response = $service->deleteService($sv_id);
        
        return redirect('/service')->with( $response['success'], $response['message'] );
    }

    public function list()
    {
        $service = new Service();
        $result  = $service->getAllServices();
        return array( 'data' => $result );
    }

}
