<?php
namespace App;

Class Matter
{
	public function getAllMatters()
	{
		// Create Soap Object
        $wsdl = env( 'ORBIT_WDSL_URL' );
        $client = new \SoapClient( $wsdl );
        
        $matters = json_decode($client->GetAllLegalMattersasJSON()->GetAllLegalMattersasJSONResult);

        foreach ($matters as $matter) {
            $result[]  = [ 
                            'MatterID'   => $matter->MatterID,
                            'MatterName' => $matter->MatterName,
                            'Tag'        => $matter->Tag,
                            'Description' => $matter->Description,
                            'ParentId'   => $matter->ParentId,
                            'ParentName' => $matter->ParentName,
                            'TypeId'     => $matter->TypeId,
                            'TypeName'   => $matter->TypeName,
                            'CreatedBy'  => $matter->CreatedBy,
                            'UpdatedBy'  => $matter ->UpdatedBy,
                            'CreatedOn'  => $matter->CreatedOn,
                            'UpdatedOn'  => $matter->UpdatedOn,
                        ];
        }

        return $result;
	}


    public function getAllMatterById( $m_id )
    {
        // Create Soap Object
        $wsdl = env( 'ORBIT_WDSL_URL' );
        $client = new \SoapClient( $wsdl );
        
        $matter = $client->GetMattersById( array( 'MatterId' => $m_id ) )->GetMattersByIdResult->LegalMatter;

        return $matter;

    }

    public function saveMatter($matter) 
    {
        // Create Soap Object
        $wsdl = env( 'ORBIT_WDSL_URL' );
        $client = new \SoapClient( $wsdl );
        
        // Create call request        
        $info = [ 'ObjectInstance' => [
                        
                        'MatterName'    => $matter['title'],
                        'Description'   => $matter['description'],
                        'ParentId'      => $matter['parent_id'], // Using 50 for the moment
                        'Tag'           => $matter['tag'],
                        'TypeId'        => $matter['lmt_id'],

                        'CreatedBy' => auth()->user()->name,
                        'CreatedOn' => '2017-05-11T16:00:00',
                        'UpdatedBy' => auth()->user()->name,
                        'UpdatedOn' => '2017-05-11T16:00:00'
                        ]                    
                ];
        
        try {

            $response = $client->SaveMatter($info);
            
            if($response->SaveMatterResult){
                return array( 'success' => 'success' , 'message' => 'New legal matter created.' );
            } else {
                return array( 'success' => 'error' , 'message' => 'something went wrong.' );
            }
        }
        catch (\Exception $e) {            
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );      
        }
    }

    public function deleteMatter($m_id)
    {

        // Create Soap Object
        $wsdl = env('ORBIT_WDSL_URL');
        $client = new \SoapClient($wsdl);
        
        // Create call request        
        $info = [ 'RefNumber' => $m_id];

        try {
            $response = $client->DeleteMatter($info);
            if($response->DeleteMatterResult){
                return array( 'success' => 'success' , 'message' => 'Legal matter deleted.' );
            } else {
                return array( 'success' => 'error' , 'message' => 'something went wrong.' );
            }
        }
        catch (\Exception $e) {            
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );       
        }
    }
}

