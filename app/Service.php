<?php
namespace App;

Class Service
{

	public function getAllServices()
	{		
		// Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();

        //GetAllOrbitServices
        $services = json_decode($client->GetAllOrbitServicesasJSON()->GetAllOrbitServicesasJSONResult, true);

        return $services;

	}

    public function saveService( $sv_params ) 
    {
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();
        
        // Current time
        $date_now = date("Y-m-d");
        $time_now = date("H:i:s");
        $date_time = $date_now . "T" . $time_now;

        $sv_params['CreatedBy'] = auth()->user()->name;
        $sv_params['UpdatedBy'] = auth()->user()->name;
        $sv_params['CreatedOn'] = $date_time;
        $sv_params['UpdatedOn'] = $date_time;

        $info = [ 'ObjectInstance' => $sv_params ];

        try {
            $response = $client->SaveOrbitService( $info );            
            // Redirect to index        
            if( $response->SaveOrbitServiceResult >= 0 ){
                return array( 'success' => 'success' , 'message' => 'Service saved.', 'data' => $response->SaveOrbitServiceResult );
            } else {
                return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        }
        catch (\Exception $e) {            
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );       
        }
    }

    public function deleteService($sv_id)
    {
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();
        
        // Create call request        
        $info = [ 'RefNumber' => $sv_id ];

        try {
            $response = $client->DeleteOrbitService( $info );
            if( $response->DeleteOrbitServiceResult ){
                return array( 'success' => 'success' , 'message' => 'Service deleted.' );
            } else {
                return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        }
        catch (\Exception $e) {            
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );       
        }

    }

    public function getServiceByID($sv_id)
    {        
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();
        
        // Create call request        
        $info = [ 'ServiceId' => $sv_id ];

        try {
            $response = $client->GetOrbitServicesWithMattersByIdasJSON( $info );
            
            if( $response->GetOrbitServicesWithMattersByIdasJSONResult ){                
                return array( 'success' => 'success' , 'message' => 'Service.', 'data' => $response->GetOrbitServicesWithMattersByIdasJSONResult );
            } else {
                return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        }
        catch (\Exception $e) {          
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );       
        }
    }

    public function getAllServicesByServiceProvider( $sp_id )
    {
        $services = self::getAllServices();

        $sp_services = [];

        foreach ($services as $service) {
            if( $service['ServiceProviderId'] == $sp_id )
            {
                $sp_services[] = $service;
            }
        }
        return $sp_services;
    }
}

