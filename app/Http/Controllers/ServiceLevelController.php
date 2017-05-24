<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServiceLevelController extends Controller
{
    
    public function index()
    {
        return view("service_level.index");
    }

    public function show()
    {
        return view("service_level.show");
    }
    
    public function create()
    {
        return view("service_level.create");
    }
    
    public function list()
    {
        return view("service_level.list");
    }

    public function destroy()
    {
        return view("service_level.destroy");
    }
}
