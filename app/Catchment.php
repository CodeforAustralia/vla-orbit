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

    public function getCatchmentByID( $ca_id )
    {
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();

        $catchments = $client->GetCatchmentById( array( 'RefNumber' => $ca_id ) )->GetCatchmentByIdResult->Catchment;        

        return $catchments;
        
    }

    public function getCatchmentsByID( $ca_id )
    {
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();

        $catchments = json_decode($client->GetCatchmentByCatchmentIdasJSON( array( 'CatchmentId' => $ca_id ) )->GetCatchmentByCatchmentIdasJSONResult, true);

        return $catchments;
        
    }
    
    public function getCatchmentsByPostCode( $postcode )
    {
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();

        $catchments = json_decode($client->GetCatchmentByPostCodeasJSON( array( 'PostCode' => $postcode ) )->GetCatchmentByPostCodeasJSONResult, true);

        return $catchments;
        
    }
    
    public function getCatchmentsBySuburb( $suburb )
    {
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();

        $catchments = json_decode($client->GetCatchmentBySuburbasJSON( array( 'Suburb' => $suburb ) )->GetCatchmentBySuburbasJSONResult, true);

        return $catchments;
        
    }

    public function setCatchmentsOnRequest( $request )
    {
        self::deleteCatchmentAreaByServiceId( $request['sv_id'] );
        
        $catchments = array();
        if( isset( $request['lga'] ) )
        {
            foreach ( $request['lga'] as $catchment_id) {
                $catchment_lgas = self::getCatchmentsByID( $catchment_id );                
                $catchments = array_merge( $catchments, $catchment_lgas ); //Check this is not efficient
            }
            self::processCatchmentArea( $catchments, $request['sv_id'], 3 );
        }

        $catchments = array();
        if( isset( $request['suburbs'] ) )
        {
            foreach ( $request['suburbs'] as $catchment_id ) 
            {
                $suburb = self::getCatchmentByID( $catchment_id );
                $catchments_suburb = self::getCatchmentsBySuburb( $suburb->Suburb ); //This is returning like %% results instead of exact matchs
                $catchments = array_merge( $catchments, $catchments_suburb ); //Check this is not efficient                            
            }
            self::processCatchmentArea( $catchments, $request['sv_id'], 2 );
        }

        $catchments = array();
        if( isset( $request['postcodes'] ) )
        {
            $postcodes = explode( ",", $request['postcodes'] );
            foreach ( $postcodes as $postcode ) 
            {
                $catchments_postcode = self::getCatchmentsByPostCode( $postcode );
                foreach ( $catchments_postcode as $temp_postcode ) 
                {
                    $catchments[ $temp_postcode['PostCode'] ] = $catchments_postcode[0];
                }            
            }            
            self::processCatchmentArea( $catchments, $request['sv_id'], 1 );
        }        
    }

    public function processCatchmentArea( $catchments, $sv_id, $ct_id ) 
    {
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();

        // Current time     
        $date_now = date("Y-m-d");
        $time_now = date("H:i:s");
        $date_time = $date_now . "T" . $time_now;
//dd($catchments);
        foreach ($catchments as $catchment) {
            if(isset($catchment['CatchmentId'])){

                $catchment_area['CatchmentAreaId'] = 0;
                $catchment_area['CatchmentId']     = $catchment['CatchmentId'];
                $catchment_area['ServiceId']       = $sv_id;
                $catchment_area['CatchmentTypeId'] = $ct_id;
                    
                // Create call request        
                $info = [ 'ObjectInstance' => $catchment_area ];

                try {
                    $response = $client->SaveCatchmentArea( $info )->SaveCatchmentAreaResult;                
                }
                catch (\Exception $e) {      
                    //return array( 'success' => 'error' , 'message' =>  $e->getMessage() );
                }
            }
        }
    }

    public function deleteCatchmentAreaByServiceId( $sv_id )
    {
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();

        // Create call request        
        $info = [ 'ServiceId' => $sv_id ];
        
        $client->DeleteCatchmentAreaByServiceId( $info );
    }

    public function sortCatchments( $catchments )
    {
        $result = [];
        $result['Suburbs'] = [];
        $result['Postcode'] = [];
        $result['LGA'] = [];

        foreach ( $catchments as $catchment ) 
        {
            switch ( $catchment->CatchmentTypeId ) 
            {
                case '2': //Suburbs                
                    if( !isset( $result['Suburbs'][ $catchment->CatchmentSuburb ] ) 
                        || $result['Suburbs'][ $catchment->CatchmentSuburb ] > $catchment->CatchmentId ){
                        $result['Suburbs'][ $catchment->CatchmentSuburb ] = $catchment->CatchmentId;
                    }                    
                    break;
                case '1': //Postcode                    
                    $result['Postcode'][] = $catchment->CatchmentPostcode;
                    break;
                case '3': //Local Goverment Council
                    # code...
                    $result['LGA'][] = $catchment->CatchmentId;
                    break;
                
                default:
                    # code...
                    break;
            }
        }

        //$result['Suburbs']  = implode( ',', array_values( $result['Suburbs'] ) ) ;
        $result['Suburbs']  = array_values( $result['Suburbs'] );
        $result['Postcode'] = implode( ',', $result['Postcode'] ) ;        
        //$result['LGA']      = implode( ',', $result['LGA'] ) ;        

        return $result;
    }

}