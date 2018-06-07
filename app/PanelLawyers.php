<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;

class PanelLawyers extends Model
{
    public $client;

    function __construct()
    {
           $this->client = (new \App\Repositories\VlaSoap)->ws_init();
    }

    /**
     * Get all Panel Lawyers
     * @return Object list of all service bookings
     */
    public function getAllPanelLawyers()
    {
        // Return the panel lawyers by subtype of law
        $panelLawyers = [];
        $subtypes = ['CHILD PROTECTION', 'FAMILY LAW', 'FAMILY VIOLENCE 29A','INDICTABLE CRIME', 'SUMMARY CRIME'];
        foreach ($subtypes as $subtype) {
            $info = [ 'SubType' => $subtype ];
            $panelLawyers = array_merge($panelLawyers,json_decode($this->client->GetPractitionersByPanelSubTypeasJSON($info)->GetPractitionersByPanelSubTypeasJSONResult,true));
         }
         $cleanArray=array();
         foreach ($panelLawyers as $key => $panelLawyer) {
            $cleanArray[] = ["OfficeId"   => $panelLawyer["OfficeId"],
                             "OfficeName" => $panelLawyer["OfficeName"],
                             "SpSubType"  => $panelLawyer["SpSubType"],
                             "LawType"    => $panelLawyer["LawType"],
                             "OfficePhone"=> $panelLawyer["OfficePhone"],
                             "FullAddress"=> $panelLawyer["FullAddress"],
                             "Website"    => $panelLawyer["Website"],
                             "lat"        => $panelLawyer["LAT"],
                             "lng"        => $panelLawyer["LONG"]];
         }
        return $cleanArray;
    }
    /**
     * getPanelLawyersGEOByPractitionerId Get practioner GEO by practitioner Id
     * @param  Integer $pGeo_Id Practitioner ID
     * @return practitioner          PractitionerGEO object
     */
    public function getPanelLawyersGEOByPractitionerId($pGeo_Id)
    {
        $info = [
            'PractitionerId'  => $pGeo_Id
        ];
        $practitioner = json_decode($this->client->GetPractitonerLGOByPractitonerIdasJSON( $info )->GetPractitonerLGOByPractitonerIdasJSONResult, true);
        return $practitioner;
    }
    /**
     * Create or Update Geographical information for practitioner
     * @param  array $info practitioner parameters
     * @return array      operation message
     */
    public function savePractitionerLatLng($info)
    {
        $info = [ 'ObjectInstance' => $info ];
        try
        {
            $response = $this->client->SavPractitonerLGO( $info );
            // Redirect to index
            if( $response->SavPractitonerLGOResult >= 0 ){
                return array( 'success' => 'success' , 'message' => 'Practitioner GEO saved.', 'data' => $response->SavPractitonerLGOResult );
            } else {
                return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        }
        catch (\Exception $e) {
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );
        }
    }
    /**
     * Delete Geografical information for a specific pratitioner
     * @param  Integer $pl_id Practitioner Id
     * @return array        operation message
     */
    public function deletePractitionerLatLng($pl_id)
    {
        $info = ['RefId' => $pl_id];
        try {
            $response = $this->client->DeletePractitonerLGO( $info );
            if( $response->DeletePractitonerLGOResult ){
                return array( 'success' => 'success' , 'message' => 'Practitioner GEO deleted.' );
            } else {
                return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        }
        catch (\Exception $e) {
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );
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
                                            ->GetAllPractitonerLGOasJSON()
                                            ->GetAllPractitonerLGOasJSONResult
                                            , true
                                        );
        return $panel_lawyers_geo;

    }

}
