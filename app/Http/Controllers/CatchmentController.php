<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Catchment;

class CatchmentController extends Controller
{
    
    public function index()
    {
        return view("catchment.index");
    }

    public function show()
    {
        return view("catchment.show");
    }

    public function store()
    {
        
    }
    
    public function create()
    {

        return view("catchment.create");
    }

    public function list()
    {
        $catchment = new Catchment();
        $result  = $catchment->getAllCatchments();
        return array( 'data' => $result );
    }

    public function listFormated()
    {
        $catchment = new Catchment();
        $result = $catchment->getAllCatchmentsFormated();
        return $result;
    }
}