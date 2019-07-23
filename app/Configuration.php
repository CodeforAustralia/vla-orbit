<?php

namespace App;

use App\Log;
use Illuminate\Support\Facades\Crypt;

/**
 * Legal Matter Model.
 * Model for the legal matter functionalities
 *
 * @author Christian Arevalo
 * @version 1.0.1
 * @see  OrbitSoap
 */

const CONFIGURATION_FILE = 'config.settings';

class Configuration extends OrbitSoap
{
    /**
     * Get all Configuration parameters
     *
     * @return array Array of Configuration parameters
     */
    public function getAllConfigurations()
    {
        try {
            $configurations = json_decode(
                $this->client
                    ->ws_init('GetAllConfigurationsasJSON')
                    ->GetAllConfigurationsasJSON()
                    ->GetAllConfigurationsasJSONResult,
                true
                                        );

            return [ 'success' => 'success' , 'data' => $configurations ];
        } catch (\Exception $e) {
            return ['success' => 'error' , 'message' =>  $e->getMessage()];
        }
    }

    /**
     * Create or update a service
     * @param Array     $request    Form paramenters with additional service information
     * @return Array    success or error message
     */
    public function save($data)
    {
        try {

            // Current time
            $date_now = date("Y-m-d");
            $time_now = date("H:i:s");
            $date_time = $date_now . "T" . $time_now;

            $data['CreatedBy'] = auth()->user()->name;
            $data['UpdatedBy'] = auth()->user()->name;
            $data['CreatedOn'] = $date_time;
            $data['UpdatedOn'] = $date_time;

            $data['Value'] = Crypt::encryptString($data['Value']);

            $query_type = (isset($data['Key']) && $data['Key'] != '' ?  'CREATE': 'UPDATE');

            $info = [ 'ObjectInstance' => $data ];

            $response = $this
                        ->client
                        ->ws_init('SaveConfigurations')
                        ->SaveConfigurations($info);
            // Redirect to index
            if ($response->SaveConfigurationsResult >= 0) {
                $log = new Log();
                $log::record($query_type, 'configuration', 0, $data);
                return [
                    'success' => 'success',
                    'message' => 'Configuration Saved'
                ];
            } else {
                return [
                    'success' => 'error',
                    'message' => 'Ups, something went wrong.'
                ];
            }
        } catch (\Exception $e) {
            return ['success' => 'error' , 'message' =>  $e->getMessage()];
        }
    }

    /**
     * Get Configuration by Key
     * @param  string   $key
     * @return array
     */
    public function getConfigurationByKey($key)
    {
        try {
            $info = [ 'Key' => $key ];

            $response = json_decode(
                $this->client
                    ->ws_init('GetConfigutationByKeyasJSON')
                    ->GetConfigutationByKeyasJSON($info)
                    ->GetConfigutationByKeyasJSONResult,
                true
                                    );

            $response = reset($response); //Get first element of the array
            if (!empty($response)) {
                $response['Value'] = Crypt::decryptString($response['Value']);

                return ['success' => 'success' , 'message' =>  $response ];
            } elseif (config('settings.' . $key)) {
                $response = [
                                'Key' => $key,
                                'Name' => '',
                                'Value' => config('settings.' . $key)
                            ];
                return ['success' => 'success' , 'message' =>  $response ];
            } else {
                return ['success' => 'error' , 'message' =>  'Key Not found'];
            }
        } catch (\Exception $e) {
            return ['success' => 'error' , 'message' =>  $e->getMessage()];
        }
    }

    /**
     * Get Configuration Value by Key
     * @param  string   $key
     * @return string value
     */
    public function getConfigurationValueByKey($key)
    {
        try {
            $info = [ 'Key' => $key ];

            $response = json_decode(
                $this->client
                    ->ws_init('GetConfigutationByKeyasJSON')
                    ->GetConfigutationByKeyasJSON($info)
                    ->GetConfigutationByKeyasJSONResult,
                true
                                    );
            $response = reset($response); //Get first element of the array
            if (!empty($response)) {
                $response['Value'] = Crypt::decryptString($response['Value']);
            } elseif (config('settings.' . $key)) {
                $response = [
                                'Value' => config('settings.' . $key)
                            ];
            } else {
                $response['Value'] = 'Key Not found';
            }
            return $response['Value'];
        } catch (\Exception $e) {
            return ['success' => 'error' , 'message' =>  $e->getMessage()];
        }
    }

    /**
     * Delete a Configuration
     * @param  string    $key Config Key
     * @return array          success or error message
     */
    public function deleteConfiguration($key)
    {
        // Create call request
        $info = [ 'Key' => $key ];

        try {
            $response = $this->client->ws_init('DeleteConfigutation')->DeleteConfigutation($info);
            if ($response->DeleteConfigutationResult) {
                $log = new Log();
                $log::record('DELETE', 'configuration', 0, 'Configuration deleted. - ' . $key);
                return array( 'success' => 'success' , 'message' => 'Configuration deleted.' );
            } else {
                return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        } catch (\Exception $e) {
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );
        }
    }
}
