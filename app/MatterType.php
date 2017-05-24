<?php
namespace App;

Class MatterType
{
	public function getAllMatterTypes()
	{
		// Create Soap Object
        $wsdl = env('ORBIT_WDSL_URL');
        $client = new \SoapClient($wsdl);
        
        $matter_types = json_decode($client->GetAllLegalMatterTypessasJSON()->GetAllLegalMatterTypessasJSONResult);

        foreach ($matter_types as $matter_type) {
            $result[]  = [ 
                            'MatterTypeID' => $matter_type->MatterTypeID,
                            'MatterTypeName' => $matter_type->MatterTypeName,
                            'CreatedBy' => $matter_type->CreatedBy,
                            'UpdatedBy' => $matter_type ->UpdatedBy,
                            'CreatedOn' => $matter_type->CreatedOn,
                            'UpdatedOn' => $matter_type->UpdatedOn,
                        ];
        }

        return $result;
	}
}

