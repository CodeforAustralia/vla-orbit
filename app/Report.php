<?php
namespace App;

Class Report
{

	public function getDashboadStats( $financial_year )
	{
		// Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();
        
        $period = [ 'DateObject' => $financial_year ]; //Financial year

        $stats = json_decode($client->GetAllStatsasJSON( $period )->GetAllStatsasJSONResult);        

        return $stats;
	}
}