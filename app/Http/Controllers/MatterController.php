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

    public function show( $m_id )
    {

        $matter_type_obj = new MatterType();
        $matter_types = $matter_type_obj->getAllMatterTypes();        

        $matter = new Matter();
        $matters = $matter->getAllMatters();

        $current_mattter = $matter->getAllMatterById( $m_id );

        return view( "matter.show", compact( 'current_mattter', 'matters', 'matter_types' ) );
    }

    public function store()
    {        
        $matter_params =    array(
                                'MatterID'     => request('MatterID'),
                                'MatterName'   => request('title'),
                                'Description'  => request('description'),
                                'ParentId'     => request('parent_id'), // Using 50 for the moment
                                'Tag'          => request('tag'),
                                'TypeId'       => request('lmt_id')
                            );
        
        $matter = new Matter();
        $response = $matter->saveMatter($matter_params);
        
        return redirect('/matter')->with($response['success'], $response['message']);
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
        $matter = new Matter();
        $response = $matter->deleteMatter($m_id);
        
        return redirect('/matter')->with($response['success'], $response['message']);
    }

    public function list()
    {
        $matter = new Matter();
        $result = $matter->getAllMatters();
        return array('data' => $result);
    }

    public function listFormated()
    {
        $matter = new Matter();
        $result = $matter->getAllMattersFormated();
        return $result;
    }
}