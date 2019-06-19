<?php
namespace App;

/**
 * Service Action model for the service action functionalities
 * @author Christian Arevalo
 * @version 1.2.1
 * @see  OrbitSoap
 */
Class ServiceAction extends OrbitSoap
{
    /**
     * Create or update service action
     * @param  String $action action
     * @param  int    $sv_id  service id
     * @param  array  $sp_ids service providers
     * @return array          success or error message
     */
    public function saveServiceAction( $action, $sv_id, $sp_ids )
    {
        try {
            $info=[];
            if ( !empty($sp_ids) ) {
                foreach ( $sp_ids as $sp_id ) {
                    $info['ObjectInstance'][] = [
                                    'Action' => $action,
                                    'Reference_id' => 0,
                                    'ServiceId' => $sv_id,
                                    'ServiceProviderId' => $sp_id,
                                ];
                }
                $response = $this
                            ->client
                            ->ws_init( 'SaveServiceActions' )
                            ->SaveServiceActions( $info );
                // Redirect to index
                if( $response->SaveServiceActionsResult != 1 ) {
                    return [ 'success' => 'error' , 'message' => 'Ups, something went wrong.' ];
                }
            }
            return [ 'success' => 'success' , 'message' => 'Service actions saved.'];
        } catch ( \Exception $e ) {
            return [ 'success' => 'error' , 'message' =>  $e->getMessage() ];
        }
    }
    /**
     * Delete service actions
     * @param  int    $sv_id service id
     * @return array         success or error message
     */
    public function deleteAllActionsByService( $sv_id )
    {
        try {
            $info = [ 'ServiceId' => $sv_id ];
            $this
            ->client
            ->ws_init( 'DeleteServiceActionsByServiceId' )
            ->DeleteServiceActionsByServiceId( $info );
        } catch ( \Exception $e ) {
            return [ 'success' => 'error' , 'message' =>  $e->getMessage() ];
        }
    }
}