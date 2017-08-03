<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use App\ServiceProvider;
use App\User;

class UserController extends Controller
{    
	public function __construct()
	{		
    	$this->middleware('auth');
	}

    public function index( Request $request )
    {
    	$request->user()->authorizeRoles('Administrator');
/*
    	$user = User::with('roles')->get();
    	$user = User::has('roles')->get();
    	$user = User::find(28);
    	$request->user()->roles()->first()->name;
    	User::where('sp_id', '=', 0)->get();
*/    	
        return view("user.index");
    }

    public function create( Request $request )
    {
    	$request->user()->authorizeRoles('Administrator');

        $service_provider_obj   = new ServiceProvider();
        $service_providers      = $service_provider_obj->getAllServiceproviders();

        if( $request->user()->sp_id != 0 ) //If the user belongs to a service provider
        {
        	foreach ( $service_providers as $service_provider ) 
        	{
        		if ( $service_provider['ServiceProviderId'] == $request->user()->sp_id  )
        		{
        			$service_providers = []; //Destroy array and override
        			$service_providers[] = $service_provider;
        		}
        	}
        }

        $role_obj = new Role();
        $roles 	  = $role_obj->all();

        return view("user.create", compact( 'service_providers', 'roles' ));
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
        if( request('sp_id') != 0 )
        {
            $user->sp_id = request('sp_id'); //No service provider
        }
        else {

            $user->sp_id = 0; //No service provider
        }

        $user->save();
        
	    //sign them in and Add role too
	    $user
	       ->roles()
	       ->attach(Role::where('id',  request('ro_id'))->first());

        return redirect('/user');
    }

    public function list()
    {
    	$users = User::with('roles')->get();
    	foreach ($users as $key => $user) {    		
    		$user['role'] = $user->roles()->first()->name;
    		$users[$key] = $user;
    	}    	
    	return [ 'data' => $users ];
    }

    public function destroy( Request $request, $uid )
    {
        $request->user()->authorizeRoles('Administrator');

        $response = User::deleteUser($uid);

        return redirect('/user')->with($response['success'], $response['message']);        
    }

    public function show( Request $request, $uid)
    {
        $request->user()->authorizeRoles('Administrator');

        $user = User::find($uid);

        $service_provider_obj   = new ServiceProvider();
        $service_providers      = $service_provider_obj->getAllServiceproviders();

        if( $request->user()->sp_id != 0 ) //If the user belongs to a service provider
        {
            foreach ( $service_providers as $service_provider ) 
            {
                if ( $service_provider['ServiceProviderId'] == $request->user()->sp_id  )
                {
                    $service_providers = []; //Destroy array and override
                    $service_providers[] = $service_provider;
                }
            }
        }

        $role_obj = new Role();
        $roles    = $role_obj->all();


        return view("user.show", compact( 'user' ,'service_providers', 'roles' ));
    }

    public function update( Request $request)
    {
        $request->user()->authorizeRoles('Administrator');
        
        $this->validate(request(),[
        
            'name'  => 'required',
            
            'email' => 'required|email',
            
            //'password' => 'required|confirmed',

            'id'    => 'required',

            'sp_id' => 'required'

        ]);        

        $response = User::updateUser( request() );
        
        return redirect('/user')->with($response['success'], $response['message']);    
    }
    
}