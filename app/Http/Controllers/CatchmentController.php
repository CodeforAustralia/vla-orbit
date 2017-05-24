<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}