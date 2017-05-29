<?php
namespace App;

Class ServiceLevel
{

	public function getAllServiceLevels()
	{		
		// Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();

        //GetAllOrbitServices
        $serviceLevels = json_decode($client->GetAllOrbitServiceLevelsasJSON()->GetAllOrbitServiceLevelsasJSONResult);
        
        foreach ($serviceLevels as $serviceLevel) {        	
        	 $result[]  = [ 
                            'ServiceLevelId' => $serviceLevel->ServiceLevelId,
                            'ServiceLevelName' => $serviceLevel->ServiceLevelName,
                            'ServiceLevelDescription' => $serviceLevel->ServiceLevelDescription,

                            'CreatedBy' => $serviceLevel->CreatedBy,
                            'UpdatedBy' => $serviceLevel ->UpdatedBy,
                            'CreatedOn' => $serviceLevel->CreatedOn,
                            'UpdatedOn' => $serviceLevel->UpdatedOn,
                        ];
        }
        return $result;

	}

    public function saveServiceLevel( $service_level ) 
    {
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();
                
        // Create call request        
        $date_now = date("Y-m-d");
        $time_now = date("H:i:s");
        $date_time = $date_now . "T" . $time_now;

        $info = [ 'ObjectInstance' => [                        
                        'ServiceLevelName'		 => $service_level['title'],
                        'ServiceLevelDescription' => $service_level['description'],

                        'CreatedBy' => auth()->user()->name,
                        'CreatedOn' => $date_time,
                        'UpdatedBy' => auth()->user()->name,
                        'UpdatedOn' => $date_time
                        ]                    
                ];
        try {
            $response = $client->SaveOrbitServiceLevel($info);
            if($response->SaveOrbitServiceLevelResult){
                return array( 'success' => 'success' , 'message' => 'Service Level created.' );
            } else {
                return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        }
        catch (\Exception $e) {            
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );           
        }
    }

    public function deleteServiceLevel($sl_id)
    {
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();
        
        // Create call request        
        $info = [ 'RefNumber' => $sl_id];

        try {
            $response = $client->DeleteOrbitServiceLevel($info);
            // Redirect to index        
            if($response->DeleteOrbitServiceLevelResult){
                return array( 'success' => 'success' , 'message' => 'Service Level deleted.' );
            } else {
                return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        }
        catch (\Exception $e) {            
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );              
        }

    }
}