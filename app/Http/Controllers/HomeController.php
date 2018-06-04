<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Home Controller.
 * Controller for the Home page
 *   
 * @author VLA & Code for Australia
 * @version 1.2.0
 * @see Controller
 */
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
}
