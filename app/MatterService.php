<?php
namespace App;

/**
 * Legal Matter Service Model.
 * Model for the legal matter interaction with Services as they are a many to many table
 *
 * @author Christian Arevalo
 * @version 1.2.0
 * @see  OrbitSoap
 */

Class MatterService extends OrbitSoap
{
    /**
     * Get all Legal matters in service
     *
     * @param integer $sv_id Service ID
     * @return array Legal matters in Service
     */
    public function getMatterServiceBySvID($sv_id)
    {
        $info = [ 'ServiceId' => $sv_id ];

        $services = json_decode(
                                    $this
                                    ->client
                                    ->ws_init('GetServiceMattersbyServiceIdasJSON')
                                    ->GetServiceMattersbyServiceIdasJSON( $info )
                                    ->GetServiceMattersbyServiceIdasJSONResult
                                );

        return $services;
	}

    /**
     * Save Legal Matters in Service
     *
     * @param integer $sv_id Service ID
     * @param integer $mt_id Legal Matter ID
     * @return array Array with error or success message
     */
    public function saveMatterService( $sv_id, $mt_id )
    {
        $info = [ 'ObjectInstance' => [
                                        'RefNo'     => 0,
        								'MatterId' 	=> $mt_id,
                                        'ServiceId' => $sv_id
        							  ]
			    ];

        try {
            $response =  $this
                        ->client
                        ->ws_init('SaveServiceMatter')
                        ->SaveServiceMatter( $info );

            if ($response->SaveServiceMatterResult) {
                return ['success' => 'success' , 'message' => 'Service created.'];
            } else {
                return ['success' => 'error' , 'message' => 'Ups, something went wrong.'];
            }
        }
        catch (\Exception $e) {
            return ['success' => 'error' , 'message' =>  $e->getMessage()];
        }
    }

    /**
     * Save Legal Matters in Service
     *
     * @param integer $sv_id Service ID
     * @param array  $matters Legal Matters ID
     * @return array Array with error or success message
     */
    public function saveMattersService( $sv_id, $matters )
    {
        try {
            foreach ( $matters as $value ) {
                $mt_id  = $value;
                $info['ObjectInstance'][] = [
                                                'RefNo'     => 0,
                                                'MatterId' 	=> $mt_id,
                                                'ServiceId' => $sv_id
                ];
            }
            if(!empty($info)) {
                $response =  $this
                            ->client
                            ->ws_init('SaveServiceMatters')
                            ->SaveServiceMatters( $info );
                if ($response->SaveServiceMattersResult) {
                    return ['success' => 'success' , 'message' => $response->SaveServiceMattersResult];
                } else {
                    return ['success' => 'error' , 'message' => 'Ups, something went wrong.'];
                }
            }
        }
        catch (\Exception $e) {
            return ['success' => 'error' , 'message' =>  $e->getMessage()];
        }
    }

    /**
     * Delete All Legal Matters by Service ID
     *
     * @param integer $sv_id Service ID
     * @return array Array with error or success message
     */
    public function deleteMatterServiceByID( $sv_id )
    {

        $info = [ 'ServiceId'     => $sv_id ] ;

        try {
            $response =  $this
                        ->client
                        ->ws_init('DeleteServiceMatterByService')
                        ->DeleteServiceMatterByService( $info );
            if ($response->DeleteServiceMatterByServiceResult) {
                return ['success' => 'success' , 'message' => 'Relation deleted.'];
            } else {
                return ['success' => 'error' , 'message' => 'Ups, something went wrong.'];
            }
        }
        catch (\Exception $e) {
            return ['success' => 'error' , 'message' =>  $e->getMessage()];
        }

    }
}