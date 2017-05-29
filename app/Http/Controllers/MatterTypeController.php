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

    public function show($mt_id)
    {
        return view("matter_type.show");
    }
    
    public function create()
    {
        return view("matter_type.create");
    }
    
    public function store() 
    {        
        $matter_type_params =    array(
                                'title'         => request('title'),
                            );
        
        $matter_type = new MatterType();
        $response = $matter_type->saveMatter($matter_type_params);
        
        return redirect('/matter_type')->with($response['success'], $response['message']);
    }

    public function destroy($mt_id)
    {

        $matter_type = new MatterType();
        $response = $matter_type->deleteMatter($mt_id);
        
        return redirect('/matter_type')->with($response['success'], $response['message']);
    }

    public function list()
    {
        $matter_type = new MatterType();

        $result = $matter_type->getAllMatterTypes();

        return array('data' => $result);
    }
    
}
