<?php
namespace App;

/**
 * Statistic model for the statistic functionalities
 * @author Christian Arevalo
 * @version 1.2.0
 * @see  OrbitSoap
 */
Class Statistic extends OrbitSoap
{
	/**
	 * Get bookings per service
	 * @return array bookings per service error otherwise
	 */
	function getOrbitBookinsPerServiceasJSON()
	{
		try {
			$response = $this
						->client
						->ws_init('GetOrbitBookinsPerServiceasJSON')
						->GetOrbitBookinsPerServiceasJSON();
			return json_decode($response->GetOrbitBookinsPerServiceasJSONResult);

		} catch( Exception $e ) {
			return [ 'success' => 'error' , 'message' =>  $e->getMessage() ];
		}
	}
	/**
	 * Get bookings with average time
	 * @return array bookings with average time, error otherwise
	 */
	function getOrbitBookinsAvgTimeasJSON()
	{
		try {
			$response = $this
						->client
						->ws_init( 'GetOrbitBookinsAvgTimeasJSON' )
						->GetOrbitBookinsAvgTimeasJSON();
			return json_decode($response->GetOrbitBookinsAvgTimeasJSONResult);
		} catch( Exception $e ) {
			return array( 'success' => 'error' , 'message' =>  $e->getMessage() );
		}
	}


}