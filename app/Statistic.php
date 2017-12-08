<?php
namespace App;

Class Statistic
{
	public $client;

	function __construct() 
	{
	       $this->client = (new \App\Repositories\VlaSoap)->ws_init();
	       //$response = $this->client->SaveBookingDocument( $info );            
	}

	function getOrbitBookinsPerServiceasJSON()
	{
		try {
			$response = $this->client->GetOrbitBookinsPerServiceasJSON();
			return json_decode($response->GetOrbitBookinsPerServiceasJSONResult);
			
		} catch (Exception $e) {
			return array( 'success' => 'error' , 'message' =>  $e->getMessage() );
		}
	}

	function getOrbitBookinsAvgTimeasJSON()
	{
		try {
			$response = $this->client->GetOrbitBookinsAvgTimeasJSON();			
			return json_decode($response->GetOrbitBookinsAvgTimeasJSONResult);
		} catch (Exception $e) {
			return array( 'success' => 'error' , 'message' =>  $e->getMessage() );
		}
	}


}