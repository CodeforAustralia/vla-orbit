<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServiceTypeController extends Controller
{
    
    public function index()
    {
        return view("service_type.index");
    }

    public function show()
    {
        return view("service_type.show");
    }
    
    public function create()
    {
        return view("service_type.create");
    }
    
    public function list()
    {
        return view("service_type.list");
    }

    public function destroy()
    {
        return view("service_type.destroy");
    }
}
