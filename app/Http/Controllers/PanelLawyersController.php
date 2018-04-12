<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use App\PanelLawyers;
use App\User;
use Auth;

class PanelLawyersController extends Controller
{    
	public function __construct()
	{		
    	$this->middleware('auth');
	}

    public function index( Request $request )
    {
        return view("panel_lawyers.index");
     
    }
    /**
     * Display all panel laywers
     * @return array list of panel lawyers
     */
    public function list()
    {        
        $panelLawyers = PanelLawyers::all();     
        return [ 'data' => $panelLawyers ];

    }
    /**
     * Open the view to create a new Panel Lawyers entry
     * @return View service booking create form
     */
    public function create()
    {
        Auth::user()->authorizeRoles('Administrator');
        return view('panel_lawyers.create');
    }

    /**
     * Show a selected panel lowyer to edit
     * @param  Integer $pl_id panel lawyer id
     * @return View        confirmation message with success or redirection otherwise
     */
    public function show( $pn_id )
    {
        Auth::user()->authorizeRoles('Administrator');
        $panelLawyers = PanelLawyers::find($pn_id);
        if(isset($panelLawyers)) 
        {
            $current_panel_lawyers = $panelLawyers;//json_decode( $result['data'] )[0];                
            return view( "panel_lawyers.show", compact( 'current_panel_lawyers') );         
        } 
        else 
        {
            return redirect('/panel_lawyers')->with( $response['success'], $response['message'] );
        }  

    }

    /**
     * Create a panel lawer
     * @return View panel lawyers view
     */
    public function store(Request $request)
    {
        $request->user()->authorizeRoles('Administrator');
        
        //Validations
        $this->validate(request(), [            
            'firm_name' => 'required|max:511',
            'address'   => 'required|max:511',
            'lat'       => 'numeric|required',
            'lng'       => 'numeric|required',
            'phone'     => 'required|numeric'
            
        ]);

        $response = PanelLawyers::createPanelLawyer( request() );

        return redirect('/panel_lawyers')->with($response['success'], $response['message']);    
    }
    /**
     * Update a panel lawer
     * @param  Request $request request to validate privileges
     * @return View panel lawyers view
     */
    public function update( Request $request)
    {
        $request->user()->authorizeRoles('Administrator');
        
        //Validations
        $this->validate(request(), [            
            'firm_name' => 'required|max:511',
            'address'   => 'required|max:511',
            'lat'       => 'numeric|required',
            'lng'       => 'numeric|required',
            'phone'     => 'required|numeric'
            
        ]);      

        $response = PanelLawyers::updatePanelLawyer( request() );
        
        return redirect('/panel_lawyers')->with($response['success'], $response['message']);    
    }

    /**
     * Delete Panel Lawyer
     * @param  Request $request request to validate privileges
     * @param  Integer  $pid     Panel Lawyer id
     * @return View panel lawyers view
     */
    public function destroy( Request $request, $pid )
    {
        $request->user()->authorizeRoles('Administrator');

        $response = PanelLawyers::deletePanelLawyer($pid);

        return redirect('/panel_lawyers')->with($response['success'], $response['message']);        
    }
    public function getClosestByAddress()
    {
        //dd(request('address'));
        $panel_obj = new PanelLawyers();
        $all = $panel_obj->all();
        $address = request('address');
        $client_address = self::getLatLngByAddress( $address );

        //dump($client_address);

        if($client_address)
        {
            $distance = array();
            foreach ($all as $key => $panel) 
            {
                if( $panel->lat !== 0)
                {   
                    $office = array('lat' => $panel->lat, 'lng' => $panel->lng);                    
                    $distance[] = [ 
                                    'distance' => self::distanceBetweenClientAndOffices( $client_address, $office),
                                    'office' => $panel->toArray()
                                  ];
                }
            }

            usort($distance, function($a, $b){ return $a["distance"] > $b["distance"]; });
            $client_address = json_encode( self::getLatLngByAddress( $address ) );
            $closest = json_encode( array_slice($distance, 0, 5, true) );

            return compact('client_address', 'closest');
        }
    }

    /**
     * Get geolocation by address
     * @param  string $address Address to get geolocation
     * @return array          Google geo location array
     */
    public function getLatLngByAddress( $address = NULL )
    {     
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );
        if(is_null($address))
        {
          $address =  request('address');
        }
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
     * [distanceBetweenClientAndOffices description]
     * @param  array $client lat and lng array
     * @param  array $office lat and lng array
     * @return float         measure of distance btw two points in kms
     */
    public function distanceBetweenClientAndOffices( $client, $office)
    {
        return self::distance($client['lat'], $client['lng'], $office['lat'], $office['lng'], 'K') ;
    }
   
   /**
    * [distance description : https://stackoverflow.com/questions/10053358/measuring-the-distance-between-two-coordinates-in-php]
    * @param  string $lat1 latitude
    * @param  string $lon1 longitude
    * @param  string $lat2 latitude
    * @param  string $lon2 longitude
    * @param  string $unit units of measure ie. kms (K),  nautic Miles (N), miles (default)
    * @return float       measure of distance btw two points in specific measure
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
}