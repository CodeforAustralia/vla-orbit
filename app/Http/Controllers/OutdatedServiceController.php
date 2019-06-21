<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Service;
use Auth;


/**
 * Outdated Service Controller.
 * Controller for the Outdated service functionalities
 * @author Christian Arevalo
 * @version 1.0.0
 * @see  Controller
 */
class OutdatedServiceController extends Controller
{
    /**
     * Service contructor. Create a new instance
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of service
     * @return view service information
     */
    public function index()
    {
        Auth::user()->authorizeRoles( ['Administrator'] );
        return view("outdated_services.index");
    }
}