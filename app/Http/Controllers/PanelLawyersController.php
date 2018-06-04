<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use App\PanelLawyers;
use App\User;
use Auth;

/**
 * Panel Lawyers Controller.
 * Controller for the private practitioners functionalities
 *   
 * @author VLA & Code for Australia
 * @version 1.2.0
 * @see  Controller
 */
class PanelLawyersController extends Controller
{
    /**
     * Panel Lawyers contructor. Create a new instance
     */      
  	public function __construct()
  	{		
      	$this->middleware('auth');
  	}
    /**
     * Display a listing of panel lawyers
     * @return view panel lawyers information
     */  
    public function index()
    {
        Auth::user()->authorizeRoles( ['Administrator']);
        return view("panel_lawyers.index");
     
    }
    /**
     * Display all panel laywers
     * @return array list of panel lawyers
     */
    public function list()
    {
        $panelLawyers = new PanelLawyers();
        $result = $panelLawyers->getAllPanelLawyers();        
        usort($result, function($a, $b){ return strcasecmp($a["OfficeId"], $b["OfficeId"]); });
        $panelLawyers = array_map("unserialize", array_unique(array_map("serialize", $result)));
        $result= [];
        // Remove some specific panel Lawyers
        $excludeList = \App\Http\helpers::getPanelLawyersRemoveList();        
        foreach ($panelLawyers as $key => $panelLawyer) {
          if(!in_array($panelLawyer['OfficeId'],$excludeList))
          {
            $result[]=$panelLawyer;
          }
          
        }

        return [ 'data' => $result ];

    }
    /**
     * Save latitude and longitude for panel lawyers
     * @return array Success Message
     */
    public function storeLatLng()
    {
      ini_set('max_execution_time', 400);
      $panelLawyers = self::list();
      $counter = 0;
      $panelLawyerObj = new PanelLawyers();
      $panelLawyersRC= []; 
      foreach ($panelLawyers['data'] as $key => $panelLawyer) 
      {                      

        $panelLawyersRC[$panelLawyer["OfficeId"]]= [
                                      'FullAddress' => $panelLawyer["FullAddress"]
                                   ];
      }             
      foreach ($panelLawyersRC as $practitionerId => $panelLawyerRC) 
      {
        $panelLawyerGEO = [];
        $info=[];
        $counter++;
        $panelLawyerGEO = $panelLawyerObj->getPanelLawyersGEOByPractitionerId($practitionerId);
        $coordinates = self::getLatLngByAddress($panelLawyerRC['FullAddress']);        
        if($counter % 100 == 0)
        {          
          sleep(1);          
        } 
        $info['PractitionerId'] = $practitionerId;
        $info['LAT'] = $coordinates['lat'];
        $info['LONG'] = $coordinates['lng'];
        if(empty($panelLawyerGEO))
        {                                        
          $info['RefId'] = 0;          
          $panelLawyerObj->savePractitionerLatLng($info);         
                   
        }
        else
        {
          foreach ($panelLawyerGEO as $key => $panlawyer) {                        
            if(strcmp( $panlawyer['LAT'] , $info['LAT'] ) != 0  || strcmp ( $panlawyer['LONG'] , $info['LONG']) != 0 )
            {
              $info['RefId'] = $panlawyer['RefId'];              
              $panelLawyerObj->savePractitionerLatLng($info);                                                      
            }
            break;
          }
          
        }         
      }        
      
      return array( 'success' => 'success' , 'message' => 'The geolocation information was saved.', 'data' =>  $counter);                
    }
    /**
     * Update panel lawyer geographical information according to the address
     * @param  integer $pl_id      panel lawyer id
     * @param  string $pl_address new panel lawyer address
     * @return array             operation message
     */
    public function updateLatLng($pl_id, $pl_address)
    {
      $panelLawyerObj = new PanelLawyers();
      $result = [];
      $info = $panelLawyerObj->getPanelLawyersGEOByPractitionerId($panelLawyer["OfficeId"]);
      if(!empty($info))
      {
        foreach ($info as $key => $inf) {
          $info['RefId'] = $inf['RefId'];
          $info['PractitionerId'] = $inf["PractitionerId"];                        
        }
        $coordinates = self::getLatLngByAddress($panelLawyerRC['FullAddress']);
        $info['LAT'] = $coordinates['lat'];
        $info['LONG'] = $coordinates['lng'];          
        $result=$panelLawyerObj->savePractitionerLatLng($info);              
      }
      return array( 'success' => 'success' , 'message' => 'The geolocation information was saved.', 'data' =>  $result);   

    }

    /**
     * Delete panel lawyer geographical information
     * @param  int $pl_id panel lawyer id 
     * @return array  Success/Error message
     */
    public function destroyLatLng($pl_id)
    {        
        $panelLawyerObj = new PanelLawyers();
        $response = $panelLawyerObj->deletePractitionerLatLng($pl_id);
        
        return array( 'success' => 'success' , 'message' => 'The geolocation information was deleted.', 'data' =>  $response );
    }        
    /**
     * Get five closest panel lawyers according to client address
     * @return array list of closest panel lawyers
     */
    public function getClosestByAddress()
    {
        //dd(request('address'));
        //$panel_obj = new PanelLawyers();
        //$all = $panel_obj->all();
        $all = self::list();        
        $address = request('address');
        $service_name = trim(request('serviceName'));
        $client_address = self::getLatLngByAddress( $address );

        //dump($client_address);
        if( strcmp(strtolower($service_name),"family violence") == 0)
        {
          $service_name = "FAMILY VIOLENCE 29A";
        }
        $service_name = strtolower($service_name);
        if($client_address)
        {
            $distance = array();
            foreach ($all['data'] as $key => $panel) 
            {                
                if( $panel['lat'] !== 0 && strcmp(strtolower($panel['SpSubType']), $service_name)== 0 )
                {   
                    $office = array('lat' => $panel['lat'], 'lng' => $panel['lng']);
                    $distance[] = [ 
                                    'distance' => self::distanceBetweenClientAndOffices( $client_address, $office),
                                    'lat' => $panel['lat'],
                                    'lng' => $panel['lng'],
                                    'office' => $panel
                                  ];
                }
            }

            usort($distance, function($a, $b){ return $a["distance"] > $b["distance"]; });
            $distance = self::travelDistance($client_address, array_slice($distance, 0, 20, true));            
            usort($distance, function($a, $b){ return $a["distance"] > $b["distance"]; });          
            $client_address = json_encode( self::getLatLngByAddress( $address ) );
            $closest = json_encode( array_slice($distance, 0, 5, true) );            
            return compact('client_address', 'closest');
        }
    }

    /**
    * Get geolocation by address
    * @param  string $address Address to get latitude and longitude
    * @return array          Google geo location array
    */
    public function getLatLngByAddress( $address)
    {     
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );

        $address = urlencode($address);

        $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . $address . '&components=locality:victoria|country:AU&key=AIzaSyAJi9SNu8Nye5MDdZcB5DfcgsZjXgJk6cc';

        $string = file_get_contents($url, false, stream_context_create($arrContextOptions)); // get json content
        $json_a = json_decode($string, true); //json decoder        
        if($json_a["status"] === "OK")
        {
            return $json_a["results"][0]["geometry"]["location"];
        }
        else
        {
            return false;
        }
    }

    /**
     * Get the distance between the client and panel lawyer.
     * @param  array $client lat and lng array
     * @param  array $office lat and lng array
     * @return float         measure of distance btw two points in kms
     */
    public function distanceBetweenClientAndOffices( $client, $office)
    {        
        return self::distance($client['lat'], $client['lng'], floatval($office['lat']), floatval($office['lng']), 'K');
    }
   
   /**
    * [distance description : https://stackoverflow.com/questions/10053358/measuring-the-distance-between-two-coordinates-in-php]
    * @param  string $lat1 client latitude 
    * @param  string $lon1 client longitude
    * @param  string $lat2 panel lawyer latitude
    * @param  string $lon2 panel lawyer longitude
    * @param  string $unit units of measure ie. kms (K),  nautic Miles (N), miles (default)
    * @return float        measure of distance btw two points in specific measure
    */
    function distance($lat1, $lon1, $lat2, $lon2, $unit) 
    {

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
          return ($miles * 1.609344);
        } else if ($unit == "N") {
          return ($miles * 0.8684);
        } else {
          return $miles;
        }
    }
    /**
     * Calculate travel distance between client and closest panel lawyers
     * @param  array $client_address client latitude and longitude
     * @param  array $distances latitude and longitude of the closest panel lawyers      
     * @return array            panel lawyers with travel distance information
     */
    public function travelDistance($client_address, $distances)
    {      
      $url = 'https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins='.$client_address['lat'].'%2C'.$client_address['lng'].'&destinations=';
      $numItems = count($distances);
      $i = 0;
      foreach ($distances as $key => $distance) {        
        if(++$i === $numItems) {
          $url.= $distance['lat'].'%2C'.$distance['lng'];
        }
        else
        {
          $url.= $distance['lat'].'%2C'.$distance['lng'].'%7C';  
        }        
      }
      $url.='&key=AIzaSyAJi9SNu8Nye5MDdZcB5DfcgsZjXgJk6cc';      
      $json_a = json_decode(file_get_contents($url), true);
      if($json_a["status"] === "OK")
      {        
        $results = $json_a['rows'][0]['elements'];
        foreach ($results as $key => $result) {
          
          $distances[$key]['distance']= $result['duration']['value'];
          
        }        
        return $distances;
      }      
      else
      {
        return false;
      }
    }

}