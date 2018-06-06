<?php

namespace App\Repositories;

use Auth;
/**
 * Role verification service.
 *
 * @author Christian Arevalo
 * @version 1.0.0
 */
class RolesCheck
{
	/**
	 * Check if the user role is administrator
	 * @return boolean true if is administrator false otherwise.
	 */
    public function is_admin()
    {
        $role = Auth::user()->roles()->first()->name;
        return ($role == 'Administrator');
    }
    /**
     * Return the role for the user in session
     * @return String user role
     */
    public function getRole()
    {
    	return Auth::user()->roles()->first()->name;
    }
}