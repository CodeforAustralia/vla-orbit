<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Auth;
use SimpleSAML_Auth_Simple;

class RegistrationController extends Controller
{

    public function __construct()
    {        
        
        //$this->middleware('auth')->except(['create','show']);
        $this->middleware('guest', ['except' => ['index']]);
                
    }

    
    public function index()
    {           
        return view("orbit.index");
    }
    
    public function create()
    {
        return view("registration.create");
    }
    
    public function store()
    {
        // Validate the form
        
        $this->validate(request(),[
        
            'name' => 'required',
            
            'email' => 'required|unique:users|email',
            
            'password' => 'required|confirmed'
            
        ]);
        
        
        //create and save the user
        
        $user = User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => bcrypt(request('password'))
        ]);
        
        //sign them in and add role
                
        $user->sp_id = 0; //No service provider
        $user->save();

        auth()->login($user);
        
        //redirect
        
        return redirect('/');
    }
    
}
