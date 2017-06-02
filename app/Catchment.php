<?php
namespace App;


Class Catchment
{
	public function getAllCatchments()
	{		
		// Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();

        //GetAllOrbitServices
        $services = json_decode($client->GetAllCatchmentsasJSON()->GetAllCatchmentsasJSONResult, true);

        return $services;
	}

    public function getAllCatchmentsFormated()
    {
        $catchments = self::getAllCatchments();

        $output = [];

        foreach ($catchments as $catchment) {            
            $output[] = [
                        'id'    => $catchment['CatchmentId'],
                        'text'  => $catchment['Suburb'] . 
                                    ' , ' . $catchment['PostCode'] .
                                    ' , ' . $catchment['LGC'] 
                        ];
        }

        return $output;
    }

}