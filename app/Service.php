<?php
namespace App;

/**
 * Service model for the service functionalities
 * @author Christian Arevalo
 * @version 1.0.0
 * @see  OrbitSoap
 */
Class Service extends OrbitSoap
{
    /**
     * Get all services
     * @return array $services services
     */
    public function getAllServices()
    {

        $services = json_decode(
                                    $this
                                    ->client
                                    ->ws_init('GetAllOrbitServicesasJSON')
                                    ->GetAllOrbitServicesasJSON()
                                    ->GetAllOrbitServicesasJSONResult
                                    , true
                                );

        //Sort by key on a multidimentional array
        usort($services, function($a, $b){ return strcmp($a["ServiceName"], $b["ServiceName"]); });

        return $services;

    }
    /**
     * Get all services by services provider
     * @param  int    $sp_id    service provider id
     * @return array  $services services by service provider
     */
    public function getAllServicesBySP( $sp_id )
    {

        $info = [
                    'ServiceProviderId'    => $sp_id
                ];

        $services = json_decode(
                                    $this
                                    ->client
                                    ->ws_init('GetAllOrbitServicesByServiceProviderasJSON')
                                    ->GetAllOrbitServicesByServiceProviderasJSON( $info )
                                    ->GetAllOrbitServicesByServiceProviderasJSONResult
                                    , true
                                );

        //Sort by key on a multidimentional array
        usort($services, function($a, $b){ return strcmp($a["ServiceName"], $b["ServiceName"]); });

        return $services;

    }
    /**
     * Get all services by service provider and service provider user
     * @param  int    $sp_id      service provider id
     * @param  int    $user_sp_id service provider user id
     * @return array  $services   services by service provider
     */
	public function getAllServicesBySPAndUserSP( $sp_id, $user_sp_id )
	{

        $info = [
                    'OwnerProviderId'   => $sp_id ,
                    'ServiceProviderId' => $user_sp_id
                ];

        $services = json_decode(
                                    $this
                                    ->client
                                    ->ws_init('GetAllEligibleOrbitServicesByProviderasJSON')
                                    ->GetAllEligibleOrbitServicesByProviderasJSON( $info )
                                    ->GetAllEligibleOrbitServicesByProviderasJSONResult
                                    , true
                                );

        //Sort by key on a multidimentional array
        usort($services, function($a, $b){ return strcmp($a["ServiceName"], $b["ServiceName"]); });

        return $services;

	}
    /**
     * Create or update a service
     * @param  Object  $sv_params service details
     * @return array              success or error message
     */
    public function saveService( $sv_params )
    {
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
            $response = $this
                        ->client
                        ->ws_init('SaveOrbitService')
                        ->SaveOrbitService( $info );
            // Redirect to index
            if ( $response->SaveOrbitServiceResult >= 0 ) {
                return [ 'success' => 'success' , 'message' => 'Service saved.', 'data' => $response->SaveOrbitServiceResult ];
            } else {
                return [ 'success' => 'error' , 'message' => 'Ups, something went wrong.' ];
            }
        } catch (\Exception $e) {
            return [ 'success' => 'error' , 'message' =>  $e->getMessage() ];
        }
    }
    /**
     * Delete a service
     * @param  int    $sv_id service id
     * @return array         success or error message
     */
    public function deleteService($sv_id)
    {

        // Create call request
        $info = [ 'RefNumber' => $sv_id ];

        try {
            $response = $this->client->ws_init('DeleteOrbitService')->DeleteOrbitService( $info );
            if ( $response->DeleteOrbitServiceResult ) {
                return array( 'success' => 'success' , 'message' => 'Service deleted.' );
            } else {
                return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        } catch ( \Exception $e ) {
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );
        }

    }
    /**
     * Gete service by service id
     * @param  int    $sv_id service id
     * @return array         service or error message
     */
    public function getServiceByID($sv_id)
    {
        // Create call request
        $info = [ 'ServiceId' => $sv_id ];

        try {
            $response =     $this
                            ->client
                            ->ws_init('GetOrbitServicesWithMattersByIdasJSON')
                            ->GetOrbitServicesWithMattersByIdasJSON( $info );

            if ( $response->GetOrbitServicesWithMattersByIdasJSONResult ) {
                return [ 'success' => 'success' , 'message' => 'Service.', 'data' => $response->GetOrbitServicesWithMattersByIdasJSONResult ];
            } else {
                return [ 'success' => 'error' , 'message' => 'Ups, something went wrong.' ];
            }
        } catch ( \Exception $e ) {
            return [ 'success' => 'error' , 'message' =>  $e->getMessage() ];
        }
    }
    /**
     * Get al service by service provider
     * @param  int    $sp_id        service provider id
     * @return array  $sp_services  services by service provider
     */
    public function getAllServicesByServiceProvider( $sp_id )
    {
        $services = self::getAllServices();

        $sp_services = [];

        foreach ( $services as $service ) {
            if ( $service['ServiceProviderId'] == $sp_id ){
                $sp_services[] = $service;
            }
        }

        return $sp_services;
    }
    /**
     * Get all services by service provider and service provider user
     * @param  int    $sp_id             service provider id
     * @param  int    $user_sp_id        service provider user id
     * @return array  $filtered_services services by service provider and service provider user
     */
    public function getAllServicesByServiceProviderAndUSerSP( $sp_id, $user_sp_id )
    {
        $services = self::getAllServicesBySP( $sp_id );

        $services_with_actions = self::getAllServicesBySPAndUserSP( $sp_id, $user_sp_id );

        $filtered_services = [];

        foreach ( $services as $service ) {

            $service_position = array_search( $service['ServiceId'],  array_column( $services_with_actions, 'ServiceId' ) );
            if ( $service_position === false ) { //The user has an action in the serice

                $service['ServiceActions'] = [];
                if ( auth()->user()->sp_id === 0 ) {
                    $service['ServiceActions'] = array(["Action" => "ALL"]);
                }
                $filtered_services[] = $service;
            } else {
                $filtered_services[] = $services_with_actions[$service_position];
            }
        }

        return $filtered_services;
    }

    /**
     * Get services information to be displayed in a data tabe format
     *
     * @param Request $request
     * @return Services
     */
    public function getServiceTable($request)
    {
        try {
            $params = [
                'PerPage' => (isset($request['per_page']) && $request['per_page'] ? $request['per_page'] : '') ,
                'Page' => (isset($request['page']) && $request['page'] ? $request['page'] - 1 : 0) ,
                'SortColumn' => (isset($request['column']) && $request['column'] ? $request['column'] : '') ,
                'SortOrder' => (isset($request['order']) && $request['order'] ? $request['order'] : '') ,
                'ColumnSearch' => '',
                'Search' => (isset($request['search']) && $request['search'] ? $request['search'] : '') ,
            ];

            $services = json_decode(
                                        $this
                                        ->client
                                        ->ws_init('GetAllServicesinBatchasJSON')
                                        ->GetAllServicesinBatchasJSON( $params )
                                        ->GetAllServicesinBatchasJSONResult
                                        , true
                                    );
            $services['data'] = self::mapServicesToFields($services['data']);
            return [ 'success' => 'success' , 'data' => $services ];
        } catch (\Exception $e) {
            return [ 'success' => 'error' , 'message' =>  $e->getMessage() ];
        }
    }

    /**
     * Map services object returned from Webservices with fields in Orbit
     *
     * @param array $services Set of services returned by the web service
     * @return array Mapped array with values in Orbit
     */
    public static function mapServicesToFields($services)
    {
        $mapped_services = [];
        foreach ($services as $service) {
            $mapped_services[] = [
                                    'sv_id' => $service['ServiceId'],
                                    'name' => $service['ServiceName'],
                                    'service_provider' => $service['ServiceProviderName'],
                                    'phone' => $service['Phone'],
                                    'email' => $service['Email'],
                                    'service_type' => $service['ServiceTypeName'],
                                    'service_level' => $service['ServiceLevelName']
                                ];
        }
        return $mapped_services;
    }
}

