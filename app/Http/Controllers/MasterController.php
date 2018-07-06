<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

/**
 * Master Controller used to test Web services.
 * Controller for the home dashboard
 *
 * @author Christian Arevalo
 * @version 1.2.0
 * @see Controller
 */
class MasterController extends Controller
{
    /**
     * Master contructor. Create a new instance
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Core Web Service client
     *
     * @return SoapClient
     */
	public function ws_init()
	{
        Auth::user()->authorizeRoles( ['Administrator']);
        // Create Soap Object
        $wsdl = env( 'ORBIT_WDSL_URL' );
        $client = new \SoapClient( $wsdl, array( 'cache_wsdl' => WSDL_CACHE_NONE ) );

        return $client;
	}

    /**
     * Web Service functions
     *
     * @return Dump Dump message on the browser
     */
    public function _functions()
    {
        Auth::user()->authorizeRoles( ['Administrator']);
    	$client = self::ws_init();
        dd( $client->__getFunctions() );
    }

    /**
     * Web Service types
     *
     * @return Dump Dump message on the browser
     */
    public function _types()
    {
        Auth::user()->authorizeRoles( ['Administrator']);
    	$client = self::ws_init();
        dd( $client->__getTypes() );
    }
}