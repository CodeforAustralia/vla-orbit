<?php
namespace App;

/**
 * Catchment model for the catchment functionalities
 * @author Christian Arevalo
 * @version 1.2.0
 * @see  OrbitSoap
 */

const POSTCODE_ID = 1;
const SUBURBS_ID = 2;
const LGA_ID = 3;
const ALL_CATCHMENTS_ID = 4;

Class Catchment extends OrbitSoap
{
    /**
     * Get all catchments from web service
     *
     * @return array Array of catchments
     */
	public function getAllCatchments()
	{
        $services = json_decode(
                                    $this
                                    ->client
                                    ->ws_init('GetAllCatchmentsasJSON')
                                    ->GetAllCatchmentsasJSON()
                                    ->GetAllCatchmentsasJSONResult
                                    , true
                                );

        foreach ($services as $pos => $service) {
            if ( $service['PostCode'] == 0 ) {
                unset( $services[$pos] );
            }
        }

        return $services;
	}

    /**
     * Get all catchments from web services
     *
     * @return array Array of catchments in format id, text (catchmend id, suburb, postcode) to be used in a select2 field
     */
    public function getAllCatchmentsFormated()
    {
        $catchments = self::getAllCatchments();

        $output = [];
        foreach ($catchments as $catchment) {
            $output[ $catchment['LGC'] ]['text'] = $catchment['LGC'];
            $output[ $catchment['LGC'] ]['children'][] = [
                        'id'    => $catchment['CatchmentId'],
                        'text'  => $catchment['Suburb'] .
                                    ', ' . $catchment['PostCode']
                        ];
        }

        return array_values($output);
    }

    /**
     * Get all distinct Local Goverment Councy
     *
     * @return array Array of Local Goverment Councy
     */
    public function getDistinctLGC()
    {
        $lgcs = self::getDistinctByKey( 'LGC' );
        return $lgcs;
    }

    /**
     * Get all distinct Postal Codes
     *
     * @return array Array of Postal Codes
     */
    public function getDistinctPostcode()
    {
        $postcodes = self::getDistinctByKey( 'PostCode' );
        return $postcodes;
    }

    /**
     * Get all distinct Suburbs
     *
     * @return array Array of Suburbs
     */
    public function getDistinctSuburb()
    {
        $suburbs = self::getDistinctByKey( 'Suburb' );
        return $suburbs;
    }

    /**
     * Get distinct Catcments by Catchment attribute (LGC, Postcode or Suburb)
     *
     * @param string $key One of LGC, Postcode or Suburb
     * @return array Array with Catchments by key
     */
    public function getDistinctByKey( $key='' )
    {
        $catchments = self::getAllCatchments();


       $temp_array = [];

       foreach ( $catchments as $catchment ) {

            if ( !isset( $temp_array[ $catchment[ $key ] ] ) ) {
                $temp_array[ $catchment[ $key ] ] = [ 'id' => $catchment['CatchmentId'] , 'text' => $catchment[ $key ] ];
            }

       }

        return array_values( $temp_array );
    }

    /**
     * Get Catchment by ID XML method
     *
     * @param string $ca_id Catchment ID
     * @return object Object of catchments by ID
     */
    public function getCatchmentByID( $ca_id )
    {
        $catchments = $this
                      ->client
                      ->ws_init('GetCatchmentById')
                      ->GetCatchmentById( ['RefNumber' => $ca_id] )
                      ->GetCatchmentByIdResult
                      ->Catchment;

        return $catchments;

    }

    /**
     * Get Catchment by ID JSON Method
     *
     * @param string $ca_id Catchment ID
     * @return array Array of catchments by ID
     */
    public function getCatchmentsByID( $ca_id )
    {
        $catchments = json_decode(
                                    $this
                                    ->client
                                    ->ws_init('GetCatchmentByCatchmentIdasJSON')
                                    ->GetCatchmentByCatchmentIdasJSON( ['CatchmentId' => $ca_id] )
                                    ->GetCatchmentByCatchmentIdasJSONResult
                                    , true
                                );

        return $catchments;

    }

    /**
     * Get Catchment by list of Id's JSON Method
     *
     * @param string $ca_id Catchment ID
     * @return array Array of catchments
     *     */
    public function getCatchmentsByListofID( $ca_ids )
    {
        $catchments = json_decode(
                                    $this
                                    ->client
                                    ->ws_init('GetCatchmentsByCatchmentIdasJSON')
                                    ->GetCatchmentsByCatchmentIdasJSON( ['CatchmentsId' => $ca_ids] )
                                    ->GetCatchmentsByCatchmentIdasJSONResult
                                    , true
                                );
        return $catchments;

    }


    /**
     * Get Catchment by PostCode
     *
     * @param string $postcode  Postcode
     * @return array    Array of Catchments by Postcode
     */
    public function getCatchmentsByPostCode( $postcode )
    {
        $catchments = json_decode(
                                    $this
                                    ->client
                                    ->ws_init('GetCatchmentByPostCodeasJSON')
                                    ->GetCatchmentByPostCodeasJSON( ['PostCode' => $postcode] )
                                    ->GetCatchmentByPostCodeasJSONResult
                                    , true
                                );

        return $catchments;

    }

    /**
     * Get Catchment by PostCode List
     *
     * @param string $postcode  Postcode List
     * @return array    Array of Catchments by Postcode
     */
    public function getCatchmentsByPostCodeList( $postcodes )
    {
        $catchments = json_decode(
                                    $this
                                    ->client
                                    ->ws_init('GetCatchmentByPostCodesasJSON')
                                    ->GetCatchmentByPostCodesasJSON( ['PostCodes' => $postcodes] )
                                    ->GetCatchmentByPostCodesasJSONResult
                                    , true
                                );

        return $catchments;

    }

    /**
     * Get Catchment by Suburb
     *
     * @param string $suburb  Suburb
     * @return array Array of Catchments by Suburb
     */
    public function getCatchmentsBySuburb( $suburb )
    {
        $catchments = json_decode(
                                    $this
                                    ->client
                                    ->ws_init('GetCatchmentBySuburbasJSON')
                                    ->GetCatchmentBySuburbasJSON( ['Suburb' => $suburb] )
                                    ->GetCatchmentBySuburbasJSONResult
                                    , true
                                );

        return $catchments;

    }

    /**
     * Get Catchment by Suburb
     *
     * @param string $suburb  Suburb
     * @return array Array of Catchments by Suburb
     */
    public function getCatchmentsBySuburbList( $ca_ids )
    {
        $catchments = json_decode(
                                    $this
                                    ->client
                                    ->ws_init('GetCatchmentBySuburbIdasJSON')
                                    ->GetCatchmentBySuburbIdasJSON( ['CatchmentsId' => $ca_ids] )
                                    ->GetCatchmentBySuburbIdasJSONResult
                                    , true
                                );

        return $catchments;

    }

    /**
     * Save Catchments on Services processiing LGA, Suburbs and Postcode
     *
     * @param array $request Array with service request
     * @param int $sv_id Service ID
     * @return void
     */
    public function setCatchmentsOnRequest( $request, $sv_id )
    {
        self::deleteCatchmentAreaByServiceId( $sv_id );

        $catchments = [];
        if ( isset( $request['lga'] ) ) {
            $catchment_lgas = self::getCatchmentsByListofID( $request['lga'] );
            $catchments = array_merge( $catchments, $catchment_lgas );
            self::processCatchmentArea( $catchments, $sv_id, LGA_ID );
        }
        $catchments = [];
        if ( isset( $request['suburbs'] ) ) {
            $catchments_suburb = self::getCatchmentsBySuburbList( $request['suburbs'] );
            $catchments = array_merge( $catchments, $catchments_suburb );
            self::processCatchmentArea( $catchments, $sv_id, SUBURBS_ID );
        }

        $catchments = [];
        if ( isset( $request['postcodes'] ) ) {
            $postcodes = explode( ",", $request['postcodes'] );
            $catchments_postcode = self::getCatchmentsByPostCodeList( $postcodes );

            foreach ( $catchments_postcode as $temp_postcode ) {
                $catchments[ $temp_postcode['PostCode'] ] = $temp_postcode;
            }
            self::processCatchmentArea( $catchments, $sv_id, POSTCODE_ID );
        }

        if ( !isset( $request['lga'] ) && !isset( $request['postcodes'] ) && !isset( $request['suburbs'] ) ) {

            $catchments[] = [
                                'CatchmentId' => 10209, //ID for all catchments
                            ];
            self::processCatchmentArea( $catchments, $sv_id, ALL_CATCHMENTS_ID ); //All catchments
        }
    }

    /**
     * Save Catment in Service
     *
     * @param array $catchments Array of catchments
     * @param string $sv_id Service ID
     * @param string $ct_id Catchment Type ID
     * @return void
     */
    public function processCatchmentArea( $catchments, $sv_id, $ct_id )
    {
        $info=[];
        $save = false;
        // Current time
        $date_now = date("Y-m-d");
        $time_now = date("H:i:s");
        $date_time = $date_now . "T" . $time_now;

        foreach ($catchments as $catchment) {

            if (isset($catchment['CatchmentId'])) {

                $catchment_area['CatchmentAreaId'] = 0;
                $catchment_area['CatchmentId']     = $catchment['CatchmentId'];
                $catchment_area['ServiceId']       = $sv_id;
                $catchment_area['CatchmentTypeId'] = $ct_id;

                // Create call request
                $info['ObjectInstance'][] = $catchment_area;
                $save = true;
            }
        }
        if($save) {
            try {
                $response = $this
                            ->client
                            ->ws_init('SaveCatchmentsArea')
                            ->SaveCatchmentsArea( $info )
                            ->SaveCatchmentsAreaResult;
            }
            catch (\Exception $e) {
                return ['success' => 'error' , 'message' =>  $e->getMessage()];
            }
        }
    }

    /**
     * Delete all Catchment information from an specific service
     *
     * @param string $sv_id Service ID
     * @return void
     */
    public function deleteCatchmentAreaByServiceId( $sv_id )
    {
        // Create call request
        $info = [ 'ServiceId' => $sv_id ];

        $this->client->ws_init('DeleteCatchmentAreaByServiceId')->DeleteCatchmentAreaByServiceId( $info );
    }

    /**
     * Sort Catchment information by type ie. Suburb, Postcode, LGA
     *
     * @param array $catchments
     * @return array Array of catchments categorized by Suburb, Postcode, LGA
     */
    public function sortCatchments( $catchments )
    {
        $result = [];
        $result['Suburbs'] = [];
        $result['Postcode'] = [];
        $result['LGA'] = [];

        foreach ( $catchments as $catchment ) {

            switch ( $catchment->CatchmentTypeId ) {

                case SUBURBS_ID: //Suburbs
                    if ( !isset( $result['Suburbs'][ $catchment->CatchmentSuburb ] )
                        || $result['Suburbs'][ $catchment->CatchmentSuburb ] > $catchment->CatchmentId ) {
                        $result['Suburbs'][ $catchment->CatchmentSuburb ] = $catchment->CatchmentId;
                    }
                    break;
                case POSTCODE_ID: //Postcode
                    $result['Postcode'][] = $catchment->CatchmentPostcode;
                    break;
                case LGA_ID: //Local Goverment Council
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