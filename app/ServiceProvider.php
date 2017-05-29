<?php
namespace App;

Class ServiceProvider
{
	public function getAllServiceProviders()
	{
		// Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();
            
        $service_providers = json_decode($client->GetAllOrbitServiceProviderssasJSON()->GetAllOrbitServiceProviderssasJSONResult);

        foreach ($service_providers as $service_provider) {
            $result[]  = [ 
                            'ServiceProviderId'     => $service_provider->ServiceProviderId,
                            'ServiceProviderName'   => $service_provider->ServiceProviderName,
                            'ContactEmail'          => $service_provider->ContactEmail,
                            'ContactName'           => $service_provider->ContactName,
                            'ContactPhone'          => $service_provider->ContactPhone,
                            'ServiceProviderAbout'  => $service_provider->ServiceProviderAbout,
                            'ServiceProviderLogo'   => $service_provider->ServiceProviderLogo,
                            'ServiceProviderURL'    => $service_provider->ServiceProviderURL,

                            'CreatedBy'     => $service_provider->CreatedBy,
                            'UpdatedBy'     => $service_provider ->UpdatedBy,
                            'CreatedOn'     => $service_provider->CreatedOn,
                            'UpdatedOn'     => $service_provider->UpdatedOn,
                        ];
        }

        return $result;
	}

    public function saveServiceProvider( $sp_params ) 
    {

        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();
        
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

        try {
            $response = $client->SaveOrbitServiceProvider( $info );
            // Redirect to index        
            if($response->SaveOrbitServiceProviderResult){
                return array( 'success' => 'success' , 'message' => 'Service provider created.' );
            } else {
                return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        }
        catch (\Exception $e) {            
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );       
        }
    }

    public function deleteServiceProvider($sp_id)
    {

        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();
        
        // Create call request        
        $info = [ 'RefNumber' => $sp_id ];

        try {
            $response = $client->DeleteOrbitServiceProvider( $info );
            if( $response->DeleteOrbitServiceProviderResult ){
                return array( 'success' => 'success' , 'message' => 'Service provider deleted.' );
            } else {
                return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        }
        catch (\Exception $e) {            
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );       
        }

    }
    
    public function ws_init() 
    {       
        // Create Soap Object
        $wsdl = env( 'ORBIT_WDSL_URL' );
        $client = new \SoapClient( $wsdl, array( 'cache_wsdl' => WSDL_CACHE_NONE ) );

        return $client;
    }    
}

