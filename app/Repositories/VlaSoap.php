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

    public function ws_booking_init() 
    {       
        // Create Soap Object
        $wsdl = env( 'ORBIT_BOOKING_WDSL_URL' );
        $client = new \SoapClient( $wsdl, array( 'cache_wsdl' => WSDL_CACHE_NONE ) );

        return $client;
    }  

    public function ws_no_reply_emails_init() 
    {       
        // Create Soap Object
        $wsdl = env( 'ORBIT_NO_REPLY_EMAILS_WDSL_URL' );
        $client = new \SoapClient( $wsdl, array( 'cache_wsdl' => WSDL_CACHE_NONE ) );

        return $client;
    }  
}