<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServiceProvidersController extends Controller
{
    
    public function index()
    {
        return view("service_providers.index");
    }

    public function show()
    {
        return view("service_providers.show");
    }
    
    public function create()
    {
        return view("service_providers.create");
    }
}
