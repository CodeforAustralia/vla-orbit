<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class MasterController extends Controller
{
	public function ws_init() 
	{		
        // Create Soap Object
        $wsdl = env( 'ORBIT_WDSL_URL' );
        $client = new \SoapClient( $wsdl, array( 'cache_wsdl' => WSDL_CACHE_NONE ) );

        return $client;
	}

    public function _functions()
    {        
    	$client = self::ws_init();
        dd( $client->__getFunctions() );
    }

    public function _types()
    {
    	$client = self::ws_init();
        dd( $client->__getTypes() );
    }
}