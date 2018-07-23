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

}