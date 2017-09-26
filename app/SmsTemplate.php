<?php

namespace App;

use App\Service;

Class SmsTemplate
{

	public function getAllTemplates()
	{		
		// Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();

        //GetAllOrbitServices
        $templates = json_decode($client->GetAllTemplatesasJSON()->GetAllTemplatesasJSONResult, true);   

        return $templates;
	}

	public function saveTemplates( $template )
	{
		// Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();

		$info = [ 'ObjectInstance' => $template ];

        try 
        {
            $response = $client->SaveTemplates( $info );         
            if( $response->SaveTemplatesResult )
            {
            	return array( 'success' => 'success' , 'message' => 'Template saved.', 'data' => $response->SaveTemplatesResult );
            } 
            else
            {
            	return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        }
        catch (\Exception $e) 
        {      
            return array( 'success' => 'error' , 'message' =>  $e->getMessage(), 'data' => $template ); 
        }
	}

	public function getTemplateByServiceId( $sv_id )
	{
		// Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();
        
		$info = [ 'ServiceId' => $sv_id ];
        $template = json_decode(
        							$client
        							->GetTemplatesByServiceIdasJSON( $info )
        							->GetTemplatesByServiceIdasJSONResult, 
        							true
        						);
        
        $templates = self::getAllTemplates();

        $current_sms_template = [];

        foreach ($templates as $template) 
        {
            if( $template['ServieId'] == $sv_id )
            {
                $current_sms_template = $template;
            }
        }       

        if( empty($current_sms_template) )
        {
            $current_sms_template = array( 'Template' => 'You have an appointment on (date) at (time) with Victoria Legal Aid. Location of appointment is at (location). To change call us on (service_phone).', 'TemplateId' => 1 );
        }

        return $current_sms_template;
	}

	public function deleteTemplateById( $st_id )
	{
		// Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();
        
		$info = [ 'RefNumber' => $st_id ];

        try 
        {
        	$response = $client->DeleteTemplate( $info );
            if( $response->DeleteTemplateResult )
            {
            	return array( 'success' => 'success' , 'message' => 'Template deleted.', 'data' => $response->DeleteTemplateResult );
            } 
            else
            {
            	return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        }
        catch (\Exception $e) 
        {      
            return array( 'success' => 'error' , 'message' =>  $e->getMessage(), 'data' => $st_id ); 
        }
	}

}