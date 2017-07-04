<?php

namespace App\Repositories;

use Auth;

class RolesCheck
{
    public function is_admin() 
    {       
        $role = Auth::user()->roles()->first()->name;        
        return ($role == 'Administrator');
    }

    public function getRole()
    {
    	return Auth::user()->roles()->first()->name;
    }
}