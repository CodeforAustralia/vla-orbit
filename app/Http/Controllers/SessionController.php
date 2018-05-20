<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SignupEmail;

class SessionController extends Controller
{
    
    public function __construct(){
        $this->middleware('guest', ['except' => 'destroy']);
    }
    
    public function create()
    {
        return view("sessions.create");
    }
    
    public function store()
    {
        
        //attempt to authenticate the user
        if(! auth()->attempt( request(['email', 'password'])) ){
            return back()->withErrors([
                'message' => 'Please check your credentials'
                ]);
        }
        
        //redirect them home
        return redirect()->home();
        
    }
    
    public function destroy()
    {
        auth()->logout();
        
        return redirect('/login');
    }
    /**
     * Send sign up email to orbit team
     * 
     */
    public function sendEmail(){
        $request = request()->all();       
        Mail::to('orbitteam@vla.vic.gov.au')->send( new SignupEmail( $request ) );
    }
    
}
