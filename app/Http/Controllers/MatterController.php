<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Matter;
use App\MatterType;

class MatterController extends Controller
{
    
    public function index()
    {
        return view("matter.index");
    }

    public function show()
    {
        return view("matter.show");
    }

    public function store()
    {
        //return request();

        // Create Soap Object
        $wsdl = env('ORBIT_WDSL_URL');
        $client = new \SoapClient($wsdl);
        
        // Create call request        
        $info = [ 'ObjectInstance' => [
                        
                        'MatterName'    => request('title'),
                        'Description'   => request('description'),
                        'ParentId'      => request('parent_id'), // Using 50 for the moment
                        'Tag'           => request('tag'),
                        'TypeId'        => request('lmt_id'),


                        'CreatedBy' => auth()->user()->name,
                        'CreatedOn' => '2017-05-11T16:00:00',
                        'UpdatedBy' => auth()->user()->name,
                        'UpdatedOn' => '2017-05-11T16:00:00'
                        ]                    
                ];

        //dd($client->__getTypes());
        //dd($client->__getFunctions());

        $response = $client->SaveMatter($info);

        try {
            // Redirect to index        
            if($response->SaveMatterResult){
                return redirect('/matter')->with('success', 'New legal matter created.');
            } else {
                return redirect('/matter')->with('error', 'something went wrong.');
            }
        }
        catch (\Exception $e) {            
            return redirect('/matter')->with('error', $e->getMessage());            
        }
        
    }
    
    public function create()
    {
        $matter_type_obj = new MatterType();
        $matter_types = $matter_type_obj->getAllMatterTypes();


        $matter_obj = new Matter();
        $matters = $matter_obj->getAllMatters();

        return view("matter.create", compact('matter_types', 'matters'));
    }

    public function destroy($m_id)
    {
        // Create Soap Object
        $wsdl = env('ORBIT_WDSL_URL');
        $client = new \SoapClient($wsdl);
        
        // Create call request        
        $info = [ 'RefNumber' => $m_id];

        $response = $client->DeleteMatter($info);
        try {
            if($response->DeleteMatterResult){
                return redirect('/matter')->with('success', 'Legal matter deleted.');
            } else {
                return redirect('/matter')->with('error', 'Ups, something went wrong.');
            }
        }
        catch (\Exception $e) {            
            return redirect('/matter')->with('error', $e->getMessage());            
        }
    }

    public function list()
    {
        $matter = new Matter();
        $result = $matter->getAllMatters();
        return array('data' => $result);
    }
}