<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
    	return request();
/*
    	// Create Soap Object
        $wsdl = env('ORBIT_WDSL_URL');
        $client = new \SoapClient($wsdl);
        
        // Create call request        
        $info = [ 'ObjectInstance' => [
                        
                        'MatterName'	=> request('title'),
                        'Description' 	=> request('description'),
                        'ParentId' 		=> request('parent_id'),
                        'Tag' 			=> request('tag'),
                        'TypeId' 		=> request('lmt_id'),


                        'CreatedBy' => auth()->user()->name,
                        'CreatedOn' => '2017-05-11T16:00:00',
                        'UpdatedBy' => auth()->user()->name,
                        'UpdatedOn' => '2017-05-11T16:00:00'
                        ]                    
                ];

        $response = $client->SaveMatter($info);

        // Redirect to index        
        if($response->SaveMatterTypeResult){
            return redirect('/matter_type')->with('status', 'New legal matter created.');
        } else {
            return redirect('/');
        }
        */
    }
    
    public function create()
    {
        $matter_type_obj = new MatterType();
        $matter_types = $matter_type_obj->getAllMatterTypes();

        return view("matter.create", compact('matter_types'));
    }
}