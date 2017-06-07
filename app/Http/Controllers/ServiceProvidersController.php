<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ServiceProvider;
use Carbon\Carbon;

class ServiceProvidersController extends Controller
{
    
    public function index()
    {
        return view("service_provider.index");
    }

    public function show($sp_id)
    {
        $service_provider_obj   = new ServiceProvider();
        $result                 = $service_provider_obj->getServiceProviderByID($sp_id);

        if(isset($result['data'])) {
            $current_sp = json_decode( $result['data'] )[0];
            return view( "service_provider.show", compact( 'current_sp' ) );         
        } else {
            return redirect('/service_provider')->with( $response['success'], $response['message'] );
        }     

    }    

    public function store()
    {                
        $sp_params = array(                    
                            'ServiceProviderId'     => request('sp_id'),
                            'ServiceProviderName'   => request('name'),
                            'ContactEmail'          => request('contact_email'),
                            'ContactName'           => request('contact_name'),
                            'ContactPhone'          => request('contact_phone'),
                            'ServiceProviderAbout'  => request('about'),
                            'ServiceProviderLogo'   => request('logo'),
                            'ServiceProviderURL'    => request('url'),
                            'ServiceProviderAddress' => request('address')
                            );
        
        $service_provider = new ServiceProvider();
        $response = $service_provider->saveServiceProvider( $sp_params );
        
        return redirect('/service_provider')->with( $response['success'], $response['message'] );
    }
    
    public function create()
    {
        return view("service_provider.create");
    }

    public function destroy($sp_id)
    {
        $service_provider = new ServiceProvider();
        $response = $service_provider->deleteServiceProvider($sp_id);
        
        return redirect('/service_provider')->with( $response['success'], $response['message'] );
    }

    public function list()
    {
        $ServiceProvider = new ServiceProvider();
        $result = $ServiceProvider->getAllServiceProviders();
        return array( 'data' => $result );
    }
}
