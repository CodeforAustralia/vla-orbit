<?php

namespace App;

use App\Service;

/**
 * Sms Template model for the sms template functionalities
 * @author Christian Arevalo
 * @version 1.2.0
 * @see  OrbitSoap
 */
Class SmsTemplate extends OrbitSoap
{
    /**
     * Get all SMS templates
     * @return array $templates sms templates
     */
	public function getAllTemplates()
	{

        //GetAllOrbitServices
        $templates = json_decode(   $this
                                    ->client
                                    ->ws_init( 'GetAllTemplatesasJSON' )
                                    ->GetAllTemplatesasJSON()
                                    ->GetAllTemplatesasJSONResult
                                    , true
                                );

        return $templates;
	}
    /**
     * Create or Update SMS template
     * @param  Object $template template details
     * @return [type]           [description]
     */
	public function saveTemplates( $template )
	{

		$info = [ 'ObjectInstance' => $template ];

        try {
            $response = $this->client->ws_init('SaveTemplates')->SaveTemplates( $info );
            if ( $response->SaveTemplatesResult ) {
            	return [ 'success' => 'success' , 'message' => 'Template saved.', 'data' => $response->SaveTemplatesResult ];
            } else {
            	return ['success' => 'error' , 'message' => 'Ups, something went wrong.' ];
            }
        } catch ( \Exception $e ){
            return [ 'success' => 'error' , 'message' =>  $e->getMessage(), 'data' => $template ];
        }
	}
    /**
     * Get templates by service
     * @param  int   $sv_id service id
     * @return array        templates
     */
	public function getTemplateByServiceId( $sv_id )
	{

		$info = [ 'ServiceId' => $sv_id ];
        $template = json_decode(
                                    $this
                                    ->client
                                    ->ws_init( 'GetTemplatesByServiceIdasJSON' )
                                    ->GetTemplatesByServiceIdasJSON( $info )
                                    ->GetTemplatesByServiceIdasJSONResult,
                                    true
                                );

        $templates = self::getAllTemplates();

        $current_sms_template = [];

        foreach ( $templates as $template ) {
            if ( $template['ServieId'] == $sv_id ) {
                $current_sms_template = $template;
            }
        }

        if ( empty($current_sms_template) ) {
            $current_sms_template = [ 'Template' => 'You have an appointment on
                                                    (date) at (time) with Victoria Legal Aid. Location of appointment is at (location).
                                                    To change call us on (service_phone).', 'TemplateId' => 1
                                    ];
        }

        return $current_sms_template;
    }

    /**
     * Get templates by service
     * @param  int   $sv_id service id
     * @return array templates
     */
	public function getTemplateByServiceBookingId( $booking_sv_id, $booking )
	{

        $service_obj = new Service();
        $services = $service_obj->getAllServices();

        $current_service = [];
        foreach ( $services as $service ) {
            if ( $service['BookingServiceId'] == $booking_sv_id
                || $service['BookingInterpritterServiceId'] == $booking_sv_id  ) {

                $current_service = $service;
            }
        }

        $sv_id = $current_service['ServiceId'];

        $templates = self::getAllTemplates();

        $current_sms_template = [];

        foreach ( $templates as $template ) {
            if ( $template['ServieId'] == $sv_id ) {
                $current_sms_template = $template;
            }
        }

        if ( empty($current_sms_template) ) {
            $current_sms_template = [ 'Template' => 'You have an appointment on
                                                    (date) at (time) with Victoria Legal Aid. Location of appointment is at (location).
                                                    To change call us on (service_phone).', 'TemplateId' => 1
                                    ];
        }

        //get client info
        $args['client_name']  = $booking['FirstName'];
        $args['client_phone'] = $booking['Mobile'];
        $args['date'] = $booking['BookingDate'];
        $args['time'] = $booking['BookingTime'];

        $args['service_name']     = $current_service['ServiceName'];
        $args['service_location'] = htmlspecialchars_decode($current_service['Location'], ENT_QUOTES);
        $args['service_phone']    = $current_service['Phone'];
        $args['template']         = $current_sms_template['Template'];

        return $this->replaceTemplateTags($args);
    }

    /**
     * Delete SMS template
     * @param  int    $st_id SMS template id
     * @return array         success or error message
     */
	public function deleteTemplateById( $st_id )
	{

		$info = [ 'RefNumber' => $st_id ];

        try {
        	$response = $this->client->ws_init('DeleteTemplate')->DeleteTemplate( $info );
            if ( $response->DeleteTemplateResult ) {
            	return [ 'success' => 'success' , 'message' => 'Template deleted.', 'data' => $response->DeleteTemplateResult ];
            } else {
            	return ['success' => 'error' , 'message' => 'Ups, something went wrong.' ];
            }
        } catch ( \Exception $e ) {
            return [ 'success' => 'error' , 'message' =>  $e->getMessage(), 'data' => $st_id ];
        }
	}

    /**
     * Replace SMS template structure
     * @param  Object  $args   template details
     * @return array   $output template modified
     */
    public function replaceTemplateTags( $args )
    {
        $output = '';
        $output  = $args['template'];

        $date      = date("D, d/m/Y", strtotime( $args['date'] ) );
        $time      = date("g:i a", strtotime( $args['time'] ) );
        $location  = $args['service_location'];
        $service_name  = $args['service_name'];
        $service_phone = $args['service_phone'];
        $client_name   = $args['client_name'];

        $output = str_replace( '(date)', $date, $output );
        $output = str_replace( '(time)', $time, $output );
        $output = str_replace( '(location)', $location, $output );
        $output = str_replace( '(client_name)', $client_name, $output );
        $output = str_replace( '(service_name)', $service_name, $output );
        $output = str_replace( '(service_phone)', $service_phone, $output );

        return $output;
    }
}