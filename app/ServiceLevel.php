<?php
namespace App;

/**
 * Service Level model for the service level functionalities
 * @author Christian Arevalo
 * @version 1.2.0
 * @see  OrbitSoap
 */
Class ServiceLevel extends OrbitSoap
{
    /**
     * Get all service levels
     * @return array $result all service levels
     */
	public function getAllServiceLevels()
	{
        //GetAllOrbitServices
        $serviceLevels = json_decode(   $this
                                        ->client
                                        ->ws_init( 'GetAllOrbitServiceLevelsasJSON' )
                                        ->GetAllOrbitServiceLevelsasJSON()
                                        ->GetAllOrbitServiceLevelsasJSONResult
                                    );

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
    /**
     * Create or update service level
     * @param  Object $service_level service level details
     * @return array                 success or error message
     */
    public function saveServiceLevel( $service_level )
    {

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
            $response = $this
                        ->client
                        ->ws_init('SaveOrbitServiceLevel')
                        ->SaveOrbitServiceLevel($info);
            if ( $response->SaveOrbitServiceLevelResult ) {
                return [ 'success' => 'success' , 'message' => 'Service Level created.' ];
            } else {
                return [ 'success' => 'error' , 'message' => 'Ups, something went wrong.' ];
            }
        } catch (\Exception $e) {
            return [ 'success' => 'error' , 'message' =>  $e->getMessage() ];
        }
    }
    /**
     * Delete service level
     * @param  int    $sl_id service level id
     * @return array         success or error message
     */
    public function deleteServiceLevel($sl_id)
    {
        // Create call request
        $info = [ 'RefNumber' => $sl_id];

        try {
             $response = $this
                        ->client
                        ->ws_init( 'DeleteOrbitServiceLevel' )
                        ->DeleteOrbitServiceLevel( $info );
            // Redirect to index
            if ( $response->DeleteOrbitServiceLevelResult ) {
                return [ 'success' => 'success' , 'message' => 'Service Level deleted.' ];
            } else {
                return [ 'success' => 'error' , 'message' => 'Ups, something went wrong.' ];
            }
        } catch (\Exception $e) {
            return [ 'success' => 'error' , 'message' =>  $e->getMessage() ];
        }

    }
}