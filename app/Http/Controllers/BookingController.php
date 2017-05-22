<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookingController extends Controller
{
    
    public function index()
    {
        return view("booking.index");
    }

    public function show()
    {
        return view("booking.show");
    }
    
    public function create()
    {
        return view("booking.create");
    }
}
