<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SignupEmail;
use App\Notifications\TwoFactorCode;


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
            return back()->withErrors( ['message' => 'Please check your credentials.'] );
        }

        /// Logic:
        //1. Generate the 6 digit varification code
        //2. send in email
        //3. redirect to 'varify' view

        //1 & 2
        self::authenticated(auth()->user());
        
        //3. redirect to varify view
        return view('auth.passwords.twoFactor');
    }

    /**
     * Destroy the user session
     * @return view redirect to login page
     */
    public function destroy()
    {
        auth()->logout();

        if(session('error')) {
            return redirect('/login')->with('error', session('error'));
        }
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

    protected function authenticated($user)
    {
        $user->generateTwoFactorCode();
        $user->notify(new TwoFactorCode());
    }

}
