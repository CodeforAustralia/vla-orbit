<?php
namespace App;

/**
 * Service Provicer model for the service provider functionalities
 * @author Christian Arevalo
 * @version 1.3.0
 * @see  OrbitSoap
 */
class ServiceProvider extends OrbitSoap
{
    /**
     * Get all service providers
     * @return array service providers
     */
    public function getAllServiceProviders()
    {
        $service_providers = json_decode(
            $this
                                            ->client
                                            ->ws_init('GetAllOrbitServiceProviderssasJSON')
                                            ->GetAllOrbitServiceProviderssasJSON()
                                            ->GetAllOrbitServiceProviderssasJSONResult,
            true
        );
        usort($service_providers, function ($a, $b) {
            return strcasecmp($a["ServiceProviderName"], $b["ServiceProviderName"]);
        });
        return $service_providers;
    }
    /**
     * Get all bookable service providers by service provider id
     * @param  int   $scope  service provicer id
     * @return array service providers
     */
    public function getAllBookableServiceProvidersById($sp_id)
    {
        $info = [ 'RefNumber' => $sp_id ];

        $service_providers = json_decode(
            $this
                                            ->client
                                            ->ws_init('GetAllOrbitBookableServiceProvidersByIdasJSON')
                                            ->GetAllOrbitBookableServiceProvidersByIdasJSON($info)
                                            ->GetAllOrbitBookableServiceProvidersByIdasJSONResult,
            true
        );
        usort($service_providers, function ($a, $b) {
            return strcasecmp($a["ServiceProviderName"], $b["ServiceProviderName"]);
        });
        return $service_providers;
    }
    /**
     * Get all bookable service providers by service provider id
     * @param  int   $scope  service provicer id
     * @return array    $output service providers formated
     */
    public function getAllBookableServiceProvidersByIdFormated($sp_id)
    {
        if ($sp_id == 0) {
            return self::getAllServiceProvidersFormated("All");
        }
        $service_providers = self::getAllBookableServiceProvidersById($sp_id);

        $output = [];

        foreach ($service_providers as $service_provider) {
            $output[] = [
                            'id'    => $service_provider['ServiceProviderId'],
                            'text'  => $service_provider['ServiceProviderName']
                        ];
        }

        return $output;
    }
    /**
     * Get all service providers formated with id and text
     * @param  String   $scope  service provicer type
     * @return array    $output service providers formated
     */
    public function getAllServiceProvidersFormated($scope)
    {
        $service_providers = self::getAllServiceProviders();

        $output = [];

        foreach ($service_providers as $service_provider) {
            $sp_type = $service_provider['ServiceProviderTypeName'];
            if ($scope === "All") {
                $output[] = [
                            'id'    => $service_provider['ServiceProviderId'],
                            'text'  => $service_provider['ServiceProviderName'],
                            'type'  => $sp_type
                            ];
            } elseif (($sp_type === "VLA" || $sp_type === "Legal Help") && $scope === "VLA") {
                //Restrict list just to VLA
                $output[] = [
                            'id'    => $service_provider['ServiceProviderId'],
                            'text'  => $service_provider['ServiceProviderName'],
                            'type'  => $sp_type
                            ];
            } elseif ($sp_type === $scope) {
                //Restrict list just to VLA
                $output[] = [
                            'id'    => $service_provider['ServiceProviderId'],
                            'text'  => $service_provider['ServiceProviderName'],
                            'type'  => $sp_type
                            ];
            }
        }

        return $output;
    }
    /**
     * Create or update service provider
     * @param  Object $sp_params service provider details
     * @return array             success or error message
     */
    public function saveServiceProvider($sp_params)
    {

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
            $response = $this
                        ->client
                        ->ws_init('SaveOrbitServiceProvider')
                        ->SaveOrbitServiceProvider($info);
            // Redirect to index
            if ($response->SaveOrbitServiceProviderResult) {
                return [ 'success' => 'success' , 'message' => 'Service provider created.' ];
            } else {
                return [ 'success' => 'error' , 'message' => 'Ups, something went wrong.' ];
            }
        } catch (\Exception $e) {
            return [ 'success' => 'error' , 'message' =>  $e->getMessage() ];
        }
    }
    /**
     * Delete service provider
     * @param  int   $sp_id service provider id
     * @return array        success or error message
     */
    public function deleteServiceProvider($sp_id)
    {
        // Create call request
        $info = [ 'RefNumber' => $sp_id ];

        try {
            $response =     $this
                            ->client
                            ->ws_init('DeleteOrbitServiceProvider')
                            ->DeleteOrbitServiceProvider($info);
            if ($response->DeleteOrbitServiceProviderResult) {
                return [ 'success' => 'success' , 'message' => 'Service provider deleted.' ];
            } else {
                return [ 'success' => 'error' , 'message' => 'Ups, something went wrong.' ];
            }
        } catch (\Exception $e) {
            return [ 'success' => 'error' , 'message' =>  $e->getMessage() ];
        }
    }
    /**
     * Get service provider by id
     * @param  int   $sp_id service provider id
     * @return array        service provider or error otherwise
     */
    public function getServiceProviderByID($sp_id)
    {
        // Create call request
        $info = [ 'RefNumber' => $sp_id ];

        try {
            $response =     $this
                            ->client
                            ->ws_init('GetOrbitServiceProviderByIdasJSON')
                            ->GetOrbitServiceProviderByIdasJSON($info);
            if ($response->GetOrbitServiceProviderByIdasJSONResult) {
                return [ 'success' => 'success' , 'message' => 'Service.', 'data' => $response->GetOrbitServiceProviderByIdasJSONResult ];
            } else {
                return [ 'success' => 'error' , 'message' => 'Ups, something went wrong.' ];
            }
        } catch (\Exception $e) {
            return [ 'success' => 'error' , 'message' =>  $e->getMessage() ];
        }
    }
}
