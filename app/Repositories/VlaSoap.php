<?php

namespace App\Repositories;

/**
 * Set the valid WSDL for the Models.
 *
 * @author Christian Arevalo, Sebastian Currea
 * @version 2.0.0
 */
class VlaSoap
{

    public $context, $login, $password, $soap_version;
    /**
     * Constructor. Initialize context, password and SOAP version
     */
    function __construct()
    {
        $this->context =  stream_context_create([
                                                    'ssl' => [
                                                        //set some SSL/TLS specific options
                                                        'verify_peer' => false,
                                                        'verify_peer_name' => false,
                                                        'allow_self_signed' => true
                                                    ]
                                                ]);
        $this->login        = env( 'ORBIT_WDSL_USER' );
        $this->password     = env( 'ORBIT_WDSL_PASSWORD' );
        $this->soap_version = SOAP_1_2;
    }

    /**
     * Set up parameters for the general services
     * @param  String $action method name.
     * @return SoapClient     Soap Client configured with the WSDL
     */
    public function ws_init( $action )
    {
        $wsdl           = env( 'ORBIT_WDSL_URL' );
        $soap_action    = env( 'ORBIT_WDSL_ACTIONS' );
        $soap_to        = env( 'ORBIT_WDSL_TO' );

        return self::getSoapClient($wsdl, $soap_action, $soap_to, $action);
    }

    /**
     * Set up parameters for booking services
     * @param  String $action method name.
     * @return SoapClient     Soap Client configured with the WSDL
     */
    public function ws_booking_init( $action )
    {
        $wsdl           = env( 'ORBIT_BOOKING_WDSL_URL' );
        $soap_action    = env( 'ORBIT_WDSL_ACTIONS_BOOKINGS' );
        $soap_to        = env( 'ORBIT_WDSL_TO_BOOKINGS' );

        return self::getSoapClient($wsdl, $soap_action, $soap_to, $action);
    }

    /**
     * Set up parameters for no reply email services
     * @param  String $action method name.
     * @return SoapClient     Soap Client configured with the parameters
     */
    public function ws_no_reply_emails_init( $action )
    {
        $wsdl           = env( 'ORBIT_NO_REPLY_EMAILS_WDSL_URL' );
        $soap_action    = env( 'ORBIT_WDSL_ACTIONS_NRE' );
        $soap_to        = env( 'ORBIT_WDSL_TO_NRE' );

        return self::getSoapClient($wsdl, $soap_action, $soap_to, $action);
    }

    /**
     * Get Soap client
     * @param  String $wsdl        WSDL address
     * @param  String $soap_action SOAP Action
     * @param  String $soap_to     SOAP service address
     * @param  String $action      Method Name
     * @return SoapClient          Soap client configured with the parameters
     */
    public function getSoapClient($wsdl, $soap_action, $soap_to, $action)
    {
        $client = new \SoapClient( $wsdl, array(
                                                 'cache_wsdl'     => WSDL_CACHE_NONE,
                                                 'login'          => $this->login,
                                                 'password'       => $this->password ,
                                                 'stream_context' => $this->context,
                                                 'soap_version'   => $this->soap_version
                                                )
                                 );

        $actionHeader = new \SoapHeader('http://www.w3.org/2005/08/addressing',
                               'Action',
                               $soap_action . $action);

        $toHeader = new \SoapHeader('http://www.w3.org/2005/08/addressing', 'To', $soap_to);
        $client->__setSoapHeaders([$actionHeader, $toHeader]);

        return $client;
    }

    /**
     * THIS METHOD IS ONLY FOR TEST LOCAL WEB SERVICES.
     *
     * @param string $wsdl
     * @return void
     */
    public function getSoapClientLocal($wsdl)
    {
        $client = new \SoapClient( $wsdl, array( 'cache_wsdl' => WSDL_CACHE_NONE ) );
        return $client;
    }
    /**
     * THIS METHOD IS ONLY FOR TEST LOCAL WEB SERVICES.
     *
     * @param [type] $action
     * @return void
     */
    public function ws_init_local( $action )
    {
        $wsdl           = env( 'ORBIT_WDSL_URL_LOCAL' );
        return self::getSoapClientLocal($wsdl);
    }
    /**
     * THIS METHOD IS ONLY FOR TEST LOCAL WEB SERVICES
     * @param  String $action method name.
     * @return SoapClient     Soap Client configured with the parameters
     */
    public function ws_no_reply_emails_init_local( $action )
    {
        $wsdl           = env( 'ORBIT_NO_REPLY_EMAILS_WDSL_URL_LOCAL' );
        return self::getSoapClientLocal($wsdl);
    }
}