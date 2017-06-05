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

        foreach ($catchments as $catchment) 
        {            
            $output[] = [
                        'id'    => $catchment['CatchmentId'],
                        'text'  => $catchment['Suburb'] . 
                                    ' , ' . $catchment['PostCode'] .
                                    ' , ' . $catchment['LGC'] 
                        ];
        }

        return $output;
    }

    public function getDistinctLGC()
    {    
        $lgcs = self::getDistinctByKey( 'LGC' );
        return $lgcs;
    }


    public function getDistinctPostcode()
    {    
        $postcodes = self::getDistinctByKey( 'PostCode' );
        return $postcodes;
    }


    public function getDistinctSuburb()
    {    
        $suburbs = self::getDistinctByKey( 'Suburb' );
        return $suburbs;
    }

    public function getDistinctByKey( $key='' )
    {
        $catchments = self::getAllCatchments();

        
       $temp_array = array();

       foreach ( $catchments as $catchment ) {

           if ( !isset( $temp_array[ $catchment[ $key ] ] ) )

           $temp_array[ $catchment[ $key ] ] = array( 'id' => $catchment['CatchmentId'] , 'text' => $catchment[ $key ] ) ;

       }

        return array_values( $temp_array ); 
    }

}