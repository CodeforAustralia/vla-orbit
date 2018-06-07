<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SignupEmail;

/**
 * Session Controller.
 * Controller for the user session functionalities
 * @author Christian Arevalo
 * @version 1.0.0
 * @see  Controller
 */
class SessionController extends Controller
{
    /**
     * Session contructor. Create a new instance
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'destroy']);
    }

    /**
     * Show the page for creating a session
     * @return view session creation page
     */
    public function create()
    {
        return view("sessions.create");
    }

    /**
     * Authorize a user to enter into the system
     * @return view redirect home page if login successful
     */
    public function store()
    {
        //attempt to authenticate the user
        if (! auth()->attempt( request(['email', 'password'])) ) {
            return back()->withErrors( ['message' => 'Please check your credentials'] );
        }

        //redirect them home
        return redirect()->home();
    }

    /**
     * Destroy the user session
     * @return view redirect to login page
     */
    public function destroy()
    {
        auth()->logout();
        return redirect('/login');
    }

    /**
     * Send sign up email to orbit team
     *
     */
    public function sendEmail()
    {
        $request = request()->all();
        Mail::to('orbitteam@vla.vic.gov.au')->send( new SignupEmail( $request ) );
    }

}
