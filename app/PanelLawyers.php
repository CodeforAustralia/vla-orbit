<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;

/**
 * Panel Lawyers model for the panel lawyers functionalities
 * @author Christian Arevalo, Sebastian Currea
 * @version 1.2.0
 * @see  OrbitSoap
 */
class PanelLawyers extends OrbitSoap
{

    /**
     * Get all Panel Lawyers. This list is obtained from ATLAS
     * @return Object list of all service bookings
     */
    public function getAllPanelLawyers()
    {
        // Return the panel lawyers by subtype of law
        $panel_lawyers = [];
        $subtypes = [ 'CHILD PROTECTION', 'FAMILY LAW', 'FAMILY VIOLENCE 29A','INDICTABLE CRIME', 'SUMMARY CRIME' ];
        foreach ( $subtypes as $subtype ) {
            $info = [ 'SubType' => $subtype ];
            $panel_lawyers = array_merge($panel_lawyers,json_decode(
                                                                        $this
                                                                        ->client
                                                                        ->ws_init( 'GetPractitionersByPanelSubTypeasJSON' )
                                                                        ->GetPractitionersByPanelSubTypeasJSON( $info )
                                                                        ->GetPractitionersByPanelSubTypeasJSONResult
                                                                        , true
                                                                    )
                                        );
         }
         $clean_array=[];
         foreach ( $panel_lawyers as $key => $panel_lawyer ) {
            $clean_array[] = [
                                "OfficeId"     => $panel_lawyer["OfficeId"],
                                "OfficeName"   => $panel_lawyer["OfficeName"],
                                "SpSubType"    => $panel_lawyer["SpSubType"],
                                "LawType"      => $panel_lawyer["LawType"],
                                "OfficePhone"  => $panel_lawyer["OfficePhone"],
                                "FullAddress"  => $panel_lawyer["FullAddress"],
                                "Website"      => $panel_lawyer["Website"],
                                "lat"          => $panel_lawyer["LAT"],
                                "lng"          => $panel_lawyer["LONG"]
                            ];
         }
        return $clean_array;
    }

    /**
     * Get practioner GEO by practitioner Id
     * @param  int          $pGeo_Id Practitioner ID
     * @return practitioner          PractitionerGEO object
     */
    public function getPanelLawyersGEOByPractitionerId( $pgeo_Id )
    {
        $info = [
                    'PractitionerId'  => $pgeo_Id
                ];
        $practitioner = json_decode(
                                        $this
                                        ->client
                                        ->ws_init('GetPractitonerLGOByPractitonerIdasJSON')
                                        ->GetPractitonerLGOByPractitonerIdasJSON( $info )
                                        ->GetPractitonerLGOByPractitonerIdasJSONResult
                                        , true
                                    );
        return $practitioner;
    }

    /**
     * Create or Update geographical information for practitioner
     * @param  array $info practitioner parameters
     * @return array       operation message
     */
    public function savePractitionerLatLng( $info )
    {
        $info = [ 'ObjectInstance' => $info ];
        try{
            $response = $this
                        ->client
                        ->ws_init( 'SavPractitonerLGO' )
                        ->SavPractitonerLGO( $info );
            // Redirect to index
            if ( $response->SavPractitonerLGOResult >= 0 ) {
                return [ 'success' => 'success' , 'message' => 'Practitioner GEO saved.', 'data' => $response->SavPractitonerLGOResult ];
            } else {
                return [ 'success' => 'error' , 'message' => 'Ups, something went wrong.' ];
            }
        }
        catch (\Exception $e) {
            return  [ 'success' => 'error' , 'message' =>  $e->getMessage() ] ;
        }
    }

    /**
     * Delete Geografical information for a specific pratitioner
     * @param  int   $pl_id Practitioner Id
     * @return array        operation message
     */
    public function deletePractitionerLatLng( $pl_id )
    {
        $info = ['RefId' => $pl_id];
        try {
            $response = $this
                        ->client
                        ->ws_init( 'DeletePractitonerLGO' )
                        ->DeletePractitonerLGO( $info );
            if ( $response->DeletePractitonerLGOResult ) {
                return [ 'success' => 'success' , 'message' => 'Practitioner GEO deleted.' ];
            } else {
                return [ 'success' => 'error' , 'message' => 'Ups, something went wrong.' ];
            }
        }
        catch ( \Exception $e) {
            return [ 'success' => 'error' , 'message' =>  $e->getMessage() ];
        }
    }
    /**
     *  Get all Panel Lawyers GEO
     * @return array panel_lawyers_geo panel lawyers geo
     */
    public function getAllPanelLawyersGEO()
    {
        $panel_lawyers_geo = json_decode(
                                            $this
                                            ->client
                                            ->ws_init( 'GetAllPractitonerLGOasJSON' )
                                            ->GetAllPractitonerLGOasJSON()
                                            ->GetAllPractitonerLGOasJSONResult
                                            , true
                                        );
        return $panel_lawyers_geo;

    }

}
