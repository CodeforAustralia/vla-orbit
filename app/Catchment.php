<?php
namespace App;


Class Catchment
{
	public function getAllCatchments()
	{		
		// Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();

        //GetAllOrbitServices
        $services = json_decode($client->GetAllCatchmentsasJSON()->GetAllCatchmentsasJSONResult);

        foreach ($services as $service) {
            $result[]  = [ 
                            'CatchmentId'   => $service->CatchmentId,
                            'Suburb'     	=> $service->Suburb,
                            'LGC'     		=> $service->LGC,
                            'PostCode'   	=> $service->PostCode,

                            'CreatedBy'     => $service->CreatedBy,
                            'UpdatedBy'     => $service->UpdatedBy,
                            'CreatedOn'     => $service->CreatedOn,
                            'UpdatedOn'     => $service->UpdatedOn,
                        ];
        }

        return $result;
	}

}