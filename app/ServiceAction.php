<?php
namespace App;

Class ServiceAction
{
	public $client;

	function __construct() 
	{
	       $this->client = (new \App\Repositories\VlaSoap)->ws_init();
	}

    public function saveServiceAction( $action, $sv_id, $sp_ids ) 
    {
        try {
        	if( !empty($sp_ids) )
        	{
		        foreach ($sp_ids as $sp_id) 
		        {
			        $sa_params['Action'] = $action;
			        $sa_params['Reference_id'] = 0;
			        $sa_params['ServiceId'] = $sv_id;
			        $sa_params['ServiceProviderId'] = $sp_id;

			        $info = [ 'ObjectInstance' => $sa_params ];

		            $response = $this->client->SaveServiceAction( $info );            
		            // Redirect to index        
		            if( $response->SaveServiceActionResult != 1 ){
		                return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
		            } 
		        }
        	}
            return array( 'success' => 'success' , 'message' => 'Service actions saved.');
        }
        catch (\Exception $e) 
        {
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );       
        }
    }

    public function deleteAllActionsByService($sv_id)
    {
    	try {

	        $info = [ 'ServiceId' => $sv_id ];
            $this->client->DeleteServiceActionsByServiceId( $info );  
    	}
        catch (\Exception $e) {            
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );       
        }
    }
}