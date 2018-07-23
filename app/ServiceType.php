<?php
namespace App;

/**
 * Service Type model for the service type functionalities
 * @author Christian Arevalo
 * @version 1.2.0
 * @see  OrbitSoap
 */
Class ServiceType extends OrbitSoap
{
    /**
     * Get all sevice types
     * @return array service types
     */
	public function getAllServiceTypes()
	{
        $serviceTypes = json_decode(    $this
                                        ->client
                                        ->ws_init( 'GetAllOrbitServiceTypesasJSON' )
                                        ->GetAllOrbitServiceTypesasJSON()
                                        ->GetAllOrbitServiceTypesasJSONResult
                                    );

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
    /**
     * Create or update the service type
     * @param  Object $service_type service type details
     * @return array                success or error message
     */
    public function saveServiceType( $service_type )
    {

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
            $response = $this
                        ->client
                        ->ws_init('SaveOrbitServiceType')
                        ->SaveOrbitServiceType($info);
            if ($response->SaveOrbitServiceTypeResult) {
                return [ 'success' => 'success' , 'message' => 'Service Type created.' ];
            } else {
                return [ 'success' => 'error' , 'message' => 'Ups, something went wrong.' ];
            }
        } catch ( \Exception $e ) {
            return [ 'success' => 'error' , 'message' =>  $e->getMessage() ];
        }
    }
    /**
     * Delete service type
     * @param  int    $st_id service type id
     * @return array         success or error message
     */
    public function deleteServiceType($st_id)
    {
        // Create call request
        $info = [ 'RefNumber' => $st_id];

        try {
            $response =     $this
                            ->client
                            ->ws_init( 'DeleteOrbitServiceType' )
                            ->DeleteOrbitServiceType($info);
            // Redirect to index
            if ( $response->DeleteOrbitServiceTypeResult ) {
                return [ 'success' => 'success' , 'message' => 'Service Type deleted.' ];
            } else {
                return [ 'success' => 'error' , 'message' => 'Ups, something went wrong.' ];
            }
        } catch ( \Exception $e ) {
            return [ 'success' => 'error' , 'message' =>  $e->getMessage() ];
        }

    }
}