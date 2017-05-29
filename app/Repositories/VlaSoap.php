<?php

namespace App\Repositories;

class VlaSoap
{
    public function ws_init() 
    {       
        // Create Soap Object
        $wsdl = env( 'ORBIT_WDSL_URL' );
        $client = new \SoapClient( $wsdl, array( 'cache_wsdl' => WSDL_CACHE_NONE ) );

        return $client;
    }  
}