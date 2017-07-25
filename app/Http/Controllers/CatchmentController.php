<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Catchment;
use Auth;

class CatchmentController extends Controller
{
    public function __construct()
    {       
        $this->middleware('auth');
    }
    
    public function index()
    {
        Auth::user()->authorizeRoles('Administrator');

        return view("catchment.index");
    }

    public function show()
    {
        Auth::user()->authorizeRoles('Administrator');

        return view("catchment.show");
    }

    public function store()
    {
        
    }
    
    public function create()
    {

        Auth::user()->authorizeRoles('Administrator');
        
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

    public function listLgcs()
    {
        $catchment = new Catchment();
        $result = $catchment->getDistinctLGC();
        return $result;
    }

    public function listSuburbs()
    {
        $catchment = new Catchment();
        $result = $catchment->getDistinctSuburb();
        return $result;
    }
}