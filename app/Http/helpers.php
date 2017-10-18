<?php
namespace App\Http;

use Auth;

class Helpers 
{

   public function VlaSimpleSAML()
   {
        $as = new SimpleSAML_Auth_Simple(env('SIMPLESML_SP'));
        $as->requireAuth();
        $attributes = $as->getAttributes();
        return $attributes;
    }

    public static function getRole()
    {
    	if( Auth::check() )
    	{
    		return Auth::user()->roles()->first()->name ;
    	} 
    	else
    	{
    		return 'Anonymous';
    	}
    }

    public static function getUSerServiceProviderId()
    {
    	if( Auth::check() )
    	{
    		return Auth::user()->sp_id ;
    	} 
    	else
    	{
    		return '';
    	}
    }
}