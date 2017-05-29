<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServiceController extends Controller
{    
    public function index()
    {
        return view("service.index");
    }

    public function show()
    {
        return view("service.show");
    }
    
    public function create()
    {
        return view("service.create");
    }
    
    public function list()
    {
        return view("service.list");
    }

    public function destroy()
    {
        return view("service.destroy");
    }   

}
