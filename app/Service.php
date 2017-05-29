<?php
namespace App;

Class Service
{

	public function getAllServices()
	{		
		// Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();

        //GetAllOrbitServices
        $services = json_decode($client->GetAllOrbitServicesasJSON()->GetAllOrbitServicesasJSONResult);

        foreach ($services as $service) {
            $result[]  = [ 
                            'ServiceId'     => $service->ServiceId,
                            'Phone'     	=> $service->Phone,
                            'Email'     	=> $service->Email,
                            'ServiceName'   => $service->ServiceName,
                            'Description'   => $service->Description,
                            'ServiceProviderId'     => $service->ServiceProviderId, 
                            'Wait'    		 => $service->Wait,
                            'LocationId'     => $service->LocationId, //This is service Level not location
                            'ServiceTypeId'  => $service->ServiceTypeId, 

                            'CreatedBy'      => $service->CreatedBy,
                            'UpdatedBy'      => $service->UpdatedBy,
                            'CreatedOn'      => $service->CreatedOn,
                            'UpdatedOn'      => $service->UpdatedOn,
                        ];
        }

        return $result;

	}


    public function saveService( $sv_params ) 
    {
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();
        
        // Create call request        
        $date_now = date("Y-m-d");
        $time_now = date("H:i:s");
        $date_time = $date_now . "T" . $time_now;
        $info = [ 'ObjectInstance' => [                        
                            'ServiceId'      => 0,
                            'ServiceName'    => $sv_params['ServiceName'],
                            'Description'    => $sv_params['Description'],
                            'Phone'     	 => $sv_params['Phone'],
                            'Email'     	 => $sv_params['Email'],
                            'Wait'    		 => $sv_params['Wait'],
                            'LocationId'     => $sv_params['LocationId'], //This is service Level not location
                            'ServiceTypeId'  => $sv_params['ServiceTypeId'], 
                            'ServiceProviderId'     => $sv_params['ServiceProviderId'], 

                            'CreatedBy'     => auth()->user()->name,
                            'UpdatedBy'     => auth()->user()->name,
                            'CreatedOn'     => $date_time,
                            'UpdatedOn'     => $date_time,
                        ]                    
                ];

        try {
            $response = $client->SaveOrbitService( $info );
            // Redirect to index        
            if($response->SaveOrbitServiceResult){
                return array( 'success' => 'success' , 'message' => 'Service created.' );
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
}

