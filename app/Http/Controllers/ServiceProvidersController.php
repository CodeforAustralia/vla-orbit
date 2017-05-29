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

    public function show()
    {
        return view("service_provider.show");
    }
    

    public function store()
    {        
        // Create Soap Object
        $wsdl = env('ORBIT_WDSL_URL');
        $client = new \SoapClient($wsdl, array('cache_wsdl' => WSDL_CACHE_NONE));
        
        // Create call request        
        $date_now = date("Y-m-d");
        $time_now = date("H:i:s");
        $date_time = $date_now . "T" . $time_now;
        $info = [ 'ObjectInstance' => [
                        
                            'ServiceProviderId'     => 0,
                            'ServiceProviderName'   => request('name'),
                            'ContactEmail'          => request('contact_email'),
                            'ContactName'           => request('contact_name'),
                            'ContactPhone'          => request('contact_phone'),
                            'ServiceProviderAbout'  => request('about'),
                            'ServiceProviderLogo'   => request('logo'),
                            'ServiceProviderURL'    => request('url'),

                            'CreatedBy'     => auth()->user()->name,
                            'UpdatedBy'     => auth()->user()->name,
                            'CreatedOn'     => $date_time,
                            'UpdatedOn'     => $date_time,
                        ]                    
                ];

        //dd($client->__getTypes());
        //dd($client->__getFunctions());

        $response = $client->SaveOrbitServiceProvider($info);

        try {
            // Redirect to index        
            if($response->SaveOrbitServiceProviderResult){
                return redirect('/service_provider')->with('success', 'New Service Provider created.');
            } else {
                dd($response);
                return redirect('/service_provider')->with('error', 'Ups, something went wrong.');
            }
        }
        catch (\Exception $e) {            
            return redirect('/service_provider')->with('error', $e->getMessage());            
        }
        
    }
    
    public function create()
    {
        return view("service_provider.create");
    }

    public function destroy($sp_id)
    {
        // Create Soap Object
        $wsdl = env('ORBIT_WDSL_URL');
        $client = new \SoapClient($wsdl, array('cache_wsdl' => WSDL_CACHE_NONE));
        
        // Create call request        
        $info = [ 'RefNumber' => $sp_id];

        $response = $client->DeleteOrbitServiceProvider($info);
        try {
            if($response->DeleteOrbitServiceProviderResult){
                return redirect('/service_provider')->with('success', 'Service Provider deleted.');
            } else {
                dd($response);
                return redirect('/service_provider')->with('error', 'Ups, something went wrong.');
            }
        }
        catch (\Exception $e) {            
            return redirect('/service_provider')->with('error', $e->getMessage());            
        }
    }

    public function list()
    {
        $ServiceProvider = new ServiceProvider();
        $result = $ServiceProvider->getAllServiceProviders();
        return array('data' => $result);
    }
}
