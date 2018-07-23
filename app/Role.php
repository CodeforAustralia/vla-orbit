<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Role model for the role functionalities
 * @author Christian Arevalo
 * @version 1.0.0
 * @see  Model
 */
class Role extends Model
{
	/**
	 * Get users with role relation
	 * @return Object roles
	 */
    public function users()
    {
      return $this
        ->belongsToMany('App\User')
        ->withTimestamps();
    }
}
