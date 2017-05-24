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

        //$matter_types = $this->getAllMatterTypes();
        return view("matter_type.index");
    }

    public function show()
    {
        return view("matter_type.show");
    }
    
    public function create()
    {
        return view("matter_type.create");
    }
    
    public function store() 
    {        
        
        // Create Soap Object
        $wsdl = env('ORBIT_WDSL_URL');
        $client = new \SoapClient($wsdl);
        
        // Create call request        
        $info = [ 'ObjectInstance' => [
                        
                        'MatterTypeName' => request('title'),
                        'CreatedBy' => auth()->user()->name,
                        'CreatedOn' => '2017-05-11T16:00:00',
                        'UpdatedBy' => auth()->user()->name,
                        'UpdatedOn' => '2017-05-11T16:00:00'
                        ]                    
                ];

        $response = $client->SaveMatterType($info);

        // Redirect to index        
        if($response->SaveMatterTypeResult){
            return redirect('/matter_type')->with('success', 'New legal matter created.');
        } else {
            return redirect('/matter_type')->with('error', 'Ups, something went wrong.');
        }
    }

    public function destroy($mt_id)
    {

        // Create Soap Object
        $wsdl = env('ORBIT_WDSL_URL');
        $client = new \SoapClient($wsdl);
        
        // Create call request        
        $info = [ 'RefNumber' => $mt_id];

        $response = $client->DeleteMatterType($info);
        
        // Redirect to index        
        if($response->DeleteMatterTypeResult){
            return redirect('/matter_type')->with('success', 'Legal matter deleted.');
        } else {
            return redirect('/matter_type')->with('error', 'Ups, something went wrong.');
        }
    }

    public function list()
    {
        $matter_type = new MatterType();

        $result = $matter_type->getAllMatterTypes();

        return array('data' => $result);
    }
    
}
