<?php
namespace App;

Class Matter
{
	public function getAllMatters()
	{
		// Create Soap Object
        $wsdl = env('ORBIT_WDSL_URL');
        $client = new \SoapClient($wsdl);
        
        $matter_types = json_decode($client->GetAllLegalMattersasJSON()->GetAllLegalMattersasJSONResult);

        foreach ($matter_types as $matter_type) {
            $result[]  = [ 
                            'MatterID' => $matter_type->MatterID,
                            'MatterName' => $matter_type->MatterName,
                            'Tag' => $matter_type->Tag,
                            'Description' => $matter_type->Description,
                            'ParentId' => $matter_type->ParentId,
                            'TypeId' => $matter_type->TypeId,
                            'TypeName' => $matter_type->TypeName,
                            'CreatedBy' => $matter_type->CreatedBy,
                            'UpdatedBy' => $matter_type ->UpdatedBy,
                            'CreatedOn' => $matter_type->CreatedOn,
                            'UpdatedOn' => $matter_type->UpdatedOn,
                        ];
        }

        return $result;
	}
}

