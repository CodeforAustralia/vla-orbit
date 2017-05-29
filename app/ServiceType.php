<?php
namespace App;

Class ServiceType
{

	public function getAllServiceTypes()
	{		
		// Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();
        $serviceTypes = json_decode( $client->GetAllOrbitServiceTypesasJSON()->GetAllOrbitServiceTypesasJSONResult );

        foreach ($serviceTypes as $serviceType) {        	
        	 $result[]  = [ 
                            'ServiceTypelId' => $serviceType->ServiceTypelId,
                            'ServiceTypeName' => $serviceType->ServiceTypeName,
                            'ServiceTypeDescription' => $serviceType->ServiceTypeDescription,

                            'CreatedBy' => $serviceType->CreatedBy,
                            'UpdatedBy' => $serviceType ->UpdatedBy,
                            'CreatedOn' => $serviceType->CreatedOn,
                            'UpdatedOn' => $serviceType->UpdatedOn,
                        ];
        }
        return $result;
	}
    
    public function saveServiceType( $service_type ) 
    {
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();
                
        // Create call request        
        $date_now = date("Y-m-d");
        $time_now = date("H:i:s");
        $date_time = $date_now . "T" . $time_now;

        $info = [ 'ObjectInstance' => [                        
                        'ServiceTypeName'		 => $service_type['title'],
                        'ServiceTypeDescription' => $service_type['description'],

                        'CreatedBy' => auth()->user()->name,
                        'CreatedOn' => $date_time,
                        'UpdatedBy' => auth()->user()->name,
                        'UpdatedOn' => $date_time
                        ]                    
                ];
        try {
            $response = $client->SaveOrbitServiceType($info);            //SaveOrbitServiceTypeResponse
            if($response->SaveOrbitServiceTypeResult){
                return array( 'success' => 'success' , 'message' => 'Service Type created.' );
            } else {
                return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        }
        catch (\Exception $e) {            
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );           
        }
    }

    public function deleteServiceType($st_id)
    {
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();
        
        // Create call request        
        $info = [ 'RefNumber' => $st_id];

        try {
            $response = $client->DeleteOrbitServiceType($info); //DeleteOrbitServiceTypeResponse
            // Redirect to index        
            if($response->DeleteOrbitServiceTypeResult){
                return array( 'success' => 'success' , 'message' => 'Service Type deleted.' );
            } else {
                return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        }
        catch (\Exception $e) {            
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );              
        }

    }
}