<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ServiceProvider;
use Carbon\Carbon;
use Auth;

class ServiceProvidersController extends Controller
{
        public function __construct()
    {       
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view("service_provider.index");
    }

    public function show($sp_id)
    {
        Auth::user()->authorizeRoles( ['Administrator', 'AdminSp'] );
        
        $is_admin = (new \App\Repositories\RolesCheck)->is_admin();
        
        if(Auth::user()->sp_id == $sp_id || $is_admin )
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
        else {
            return redirect('/service_provider')->with( 'error', 'Not Authorized' );
        }
    }    

    public function store()
    {
        Auth::user()->authorizeRoles( ['Administrator', 'AdminSp'] );
        $sp_params = array(                    
                            'ServiceProviderId'     => request('sp_id'),
                            'ServiceProviderName'   => request('name'),
                            'ContactEmail'          => request('contact_email'),
                            'ContactName'           => request('contact_name'),
                            'ContactPhone'          => request('contact_phone'),
                            'ServiceProviderAbout'  => request('about'),
                            'ServiceProviderLogo'   => request('logo'),
                            'ServiceProviderURL'    => request('url'),
                            'ServiceProviderAddress' => request('address'),
                            'ServiceProviderTypeId' => request('spt_id')
                            );
        
        $service_provider = new ServiceProvider();
        $response = $service_provider->saveServiceProvider( $sp_params );
        
        return redirect('/service_provider')->with( $response['success'], $response['message'] );
    }
    
    public function create()
    {
        Auth::user()->authorizeRoles('Administrator');

        return view("service_provider.create");
    }

    public function destroy($sp_id)
    {
        Auth::user()->authorizeRoles('Administrator');

        $service_provider = new ServiceProvider();
        $response = $service_provider->deleteServiceProvider($sp_id);
        
        return redirect('/service_provider')->with( $response['success'], $response['message'] );
    }

    public function list()
    {
        $service_provider = new ServiceProvider();
        $result = $service_provider->getAllServiceProviders();
        return array( 'data' => $result );
    }
    
    public function listFormated()
    {
        $service_provider = new ServiceProvider();
        $result = $service_provider->getAllServiceProvidersFormated( request('scope') );
        return $result;
    }
}
