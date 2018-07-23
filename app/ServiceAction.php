<?php
namespace App;

/**
 * Service Action model for the service action functionalities
 * @author Christian Arevalo
 * @version 1.2.0
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
        	if ( !empty($sp_ids) ) {
		        foreach ( $sp_ids as $sp_id ) {
			        $sa_params['Action'] = $action;
			        $sa_params['Reference_id'] = 0;
			        $sa_params['ServiceId'] = $sv_id;
			        $sa_params['ServiceProviderId'] = $sp_id;

			        $info = [ 'ObjectInstance' => $sa_params ];

		            $response = $this
                                ->client
                                ->ws_init( 'SaveServiceAction' )
                                ->SaveServiceAction( $info );
		            // Redirect to index
		            if( $response->SaveServiceActionResult != 1 ) {
		                return [ 'success' => 'error' , 'message' => 'Ups, something went wrong.' ];
		            }
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