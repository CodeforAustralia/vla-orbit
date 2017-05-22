<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use GuzzleHttp\Exception\GuzzleException;
//use GuzzleHttp\Client;
use App\MatterType;

class MatterTypeController extends Controller
{
    
    public function index()
    {
        /*
        $client = new Client(); //GuzzleHttp\Client
        $result = $client->post('https://orbitservicehost.vla.vic.gov.au/VLA.Orbit.ServiceHost.OrbitService.svc?wsdl', [
            'form_params' => [
                'sample-form-data' => 'value'
            ]
        ]);*/
        /*
	$wsdl = 'https://orbitservicehost.vla.vic.gov.au/VLA.Orbit.ServiceHost.OrbitService.svc?wsdl';
        $client = new \SoapClient($wsdl);
	
	$info = [ 
			'CreatedBy' => 'Christian Arevalo',
			'CreatedOn' => '2017-05-11T16:00:00',
			'MatterTypeID' => '2',
			'MatterTypeName' => 'First Legal matter type',
			'UpdatedBy' => 'Christian Arevalo',
			'UpdatedOn' => '2017-05-11T16:00:00'
		];


    $response = $client->__soapCall("SaveMatterType", $info);
    //$response = $client->SaveMatterType($info);
    */

    $wsdl_url = 'https://orbitservicehost.vla.vic.gov.au/VLA.Orbit.ServiceHost.OrbitService.svc?wsdl';
    $client = new \SoapClient($wsdl_url);
    $params = array(
                    
                    'MatterTypeName' =>'Test 12345',
                    'UpdatedBy' =>'Tester',
                    'UpdatedOn'=>'2017-05-11T16:00:00',
                    'CreatedBy' =>'Tester',
                    'CreatedOn'=>'2017-05-11T16:00:00'
   );
    $result = $client->SaveMatterType($params);
    //$result = $client->__soapCall("SaveMatterType", $params);
    dd($result->SaveMatterType);




       return view("matter_type.index", compact('client','response','oi'));
    }

    public function show()
    {
        return view("matter_type.show");
    }
    
    public function create()
    {
        return view("matter_type.create");
    }
    
    public function store() {
        dd(request('title'));
        
        // Create Soap Object
        
        // Create call request
        
        // Redirect to index
    }
    
}
