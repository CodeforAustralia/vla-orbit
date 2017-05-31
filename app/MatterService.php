<?php
namespace App;

Class MatterService
{

	public function getMatterServiceBySvID($sv_id) {
		// Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();

        //GetAllOrbitServices
        $info = [ 'ServiceId' => $sv_id ];

        $services = json_decode($client->GetServiceMattersbyServiceIdasJSON( $info )->GetServiceMattersbyServiceIdasJSONResult);        

        return $services;
	}


    public function saveMatterService( $sv_id, $mt_id ) 
    {
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();

        $info = [ 'ObjectInstance' => [    
                                        'RefNo'     => 0,
        								'MatterId' 	=> $mt_id,
                                        'ServiceId' => $sv_id
        							  ]
			    ];

        try {
            $response = $client->SaveServiceMatter( $info );            
            if($response->SaveServiceMatterResult){
                return array( 'success' => 'success' , 'message' => 'Service created.' );
            } else {
                return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        }
        catch (\Exception $e) {            
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );       
        }
    }
}