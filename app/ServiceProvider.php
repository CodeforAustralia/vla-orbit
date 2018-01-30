<?php
namespace App;

Class ServiceProvider
{
	public function getAllServiceProviders()
	{
		// Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();
            
        $service_providers = json_decode($client->GetAllOrbitServiceProviderssasJSON()->GetAllOrbitServiceProviderssasJSONResult, true);
        usort($service_providers, function($a, $b){ return strcasecmp($a["ServiceProviderName"], $b["ServiceProviderName"]); });
        return $service_providers;
	}

    public function saveServiceProvider( $sp_params ) 
    {

        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();
        
        // Current time     
        $date_now = date("Y-m-d");
        $time_now = date("H:i:s");
        $date_time = $date_now . "T" . $time_now;

        $sp_params['CreatedBy'] = auth()->user()->name;     
        $sp_params['UpdatedBy'] = auth()->user()->name;     
        $sp_params['CreatedOn'] = $date_time;       
        $sp_params['UpdatedOn'] = $date_time;     

        $info = [ 'ObjectInstance' => $sp_params ];

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

    public function getServiceProviderByID($sp_id)
    {        
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();
        
        // Create call request        
        $info = [ 'RefNumber' => $sp_id ];

        try {
            $response = $client->GetOrbitServiceProviderByIdasJSON( $info );
            if( $response->GetOrbitServiceProviderByIdasJSONResult ){
                return array( 'success' => 'success' , 'message' => 'Service.', 'data' => $response->GetOrbitServiceProviderByIdasJSONResult );
            } else {
                return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        }
        catch (\Exception $e) {            
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );       
        }
    }
}

