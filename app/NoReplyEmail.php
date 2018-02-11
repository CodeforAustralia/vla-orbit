<?php
namespace App;

use Illuminate\Support\Facades\Mail;

use Auth;
use App\ServiceProvider;
use App\Mail\NoReplyEmailMailable;

class NoReplyEmail
{
	public $client;

	function __construct() 
	{
	       $this->client = (new \App\Repositories\VlaSoap)->ws_no_reply_emails_init();
	}

	public function getAllTemplates()
	{
		try 
		{
			$user = auth()->user();
			//dd(session('login_vla_attributes'));
			$response =  $this->client->GetAllTemplatesasJSON() ;			
			return json_decode( $response->GetAllTemplatesasJSONResult, true );
		} 
		catch (Exception $e) 
		{
			return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );			
		}
	}

	public function getTemplateById( $template_id )
	{
		try 
		{
			$templates = self::getAllTemplates();

			foreach ($templates as $template) 
			{
				if( $template['RefNo'] == $template_id )
				{
					return $template;
				}
			}
			return false;
		} 
		catch (Exception $e) 
		{
			return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );		
		}
	}

	public function getAllTemplatesBySection()
	{
		try 
		{
			$data = [];
			if( auth()->user()->sp_id != 0)
			{				
				$section = self::getSection();			
				$info = [ 'Section' => $section ];
				$response = $this->client->GetTemplatesBySectionasJSON( $info );
				$templates = json_decode( $response->GetTemplatesBySectionasJSONResult, true );

				foreach ($templates as $template) 
				{
					if($template['RefNo'] > 0)
					{
						$data[] = $template;
					}
				}
			}
			else
			{
				$templates = self::getAllTemplates();
				array_shift( $templates ); // Remove first element of array as it is returning an empty element
				$data = $templates;
			}
			return ['data' => $data ];
		} 
		catch (Exception $e) 
		{
			return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );		
		}
	}

	public function getAllMailBoxes()
	{
		try 
		{
			$response = $this->client->GetAllMailBoxesasJSON();
			return json_decode( $response->GetAllMailBoxesasJSONResult, true );
		} 
		catch (Exception $e) 
		{
			return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );		
		}
	}

	public function sendEmail( $email_data )
	{	
		try 
		{
	        // Current time
	        $date_now  = date("Y-m-d");
	        $time_now  = date("H:i:s");
	        $date_time = $date_now . "T" . $time_now;

	       	$files = array();

	       	if ( isset($email_data['attachments']) )
	       	{
	       		$files = $email_data['attachments'];
	       	}

	       	if( isset($email_data['main_attachment']) )
	       	{
	       		$files[]['files'] = $email_data['main_attachment'];
	       	}

	       	$attachments = self::attachFiles( $files );

	       	$prefix = '<p><span style="font-family:Arial,Helvetica,sans-serif"><em><em>This email was sent by Orbit to</em> ' . $email_data['to'] .  ' </em></span></p><p><span style="font-family:Arial,Helvetica,sans-serif"><em>Please do not reply to this email.</em>&nbsp;</span></p><hr>';

	       	$email_data['message'] = $prefix . $email_data['message'];

			$info = [
						'MessageObject' => [
												'Attachments' 	=> $attachments,
												'Body' 			=> $email_data['message'],
												'Deliverd' 		=> 1,
												'Error' 		=> 0,
												'FromAddress' 	=> 'noreply@vla.vic.gov.au',
												'PersonID' 		=> auth()->user()->id,
												'RefNo' 		=> 0,
												'Section' 		=> self::getSection(),
												'SentOn' 		=> $date_time,
												'Subject' 		=> $email_data['subject'],
												'ToAddress' 	=> $email_data['to'],
											],
						'IsHTML'		=> true,
					];
			
			$response = $this->client->SendEmailasJSON($info);
			Mail::to( auth()->user()->email )->send( new NoReplyEmailMailable( $email_data ) );
			//return json_decode( $response->SendEmailasJSONResult, true );
			return array( 'success' => 'success' , 'message' => 'The email was sent.' );
		} 
		catch (Exception $e) 
		{
			return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
		}
	}

    public function attachFiles( $files )
    {
    	$attachments = [];

    	foreach ($files as $current_file) {
    		$file = $current_file['files'];
    		$handle = fopen($file->getPathName(), "rb");                  // Open the temp file

	       	$content = fread( $handle, filesize($file->getPathName()) );  // Read the temp file

	       	fclose($handle);

	       	$attachments[] = [ 'AttachmentBytes' => $content, 'FileName' => $file->getClientOriginalName() ];
	       	
    	}
    	return $attachments;
    }
    
	public function saveEmailTemplate( $data )
	{
		try 
		{	
	        // Current time
	        $date_now  = date("Y-m-d");
	        $time_now  = date("H:i:s");
	        $date_time = $date_now . "T" . $time_now;

		   	$section = '';

		   	if( isset($data['Section']) && $data['Section'] != '' && !isset($data['all']) )
		   	{
		   		$section = $data['Section'];
		   	}
		   	elseif( isset($data['all']) && $data['all'] == 'on' )
		   	{
		   		$section = 'All';
		   	}
		   	else
		   	{
		   		$section = self::getSection();
		   	}

			$template =  [
								'RefNo' 		=> $data['RefNo'],
								'Created' 		=> $date_time,
								'CreatedBy' 	=> auth()->user()->id,
								'Name' 			=> $data['name'],
								'Section' 		=> $section,
								'Subject' 		=> $data['subject'],
								'TemplateText' 	=> $data['template'],
								'Updated' 		=> $date_time,
								'UpdatedBy' 	=> auth()->user()->id,
						   ];

       		$info = [ 'ObjectInstance' => $template ];
       		$response = $this->client->SaveEmailTemplate( $info );

			return array( 'success' => 'success' , 'message' => 'The template was saved.', 'data' => $response );
			
		} 
		catch (Exception $e) 
		{
			return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );		
		}
	}
	public function saveFromAddress()
	{
		try {
	        // Current time
	        $date_now  = date("Y-m-d");
	        $time_now  = date("H:i:s");
	        $date_time = $date_now . "T" . $time_now;
/*
			$address =  [
								'RefNo' 		=> 0,
								'Created' 		=> $date_time,
								'CreatedBy' 	=> auth()->user()->name,
								'Code' 			=> $data['code'],								
								'Value' 		=> $data['value'],
								'Updated' 		=> $date_time,
								'UpdatedBy' 	=> auth()->user()->name,
						   ];*/
			$address =  [
								'RefNo' 		=> 0,
								'Created' 		=> $date_time,
								'CreatedBy' 	=> auth()->user()->name,
								'Code' 			=> 'test',								
								'Value' 		=> 'test@test.com',
								'Updated' 		=> $date_time,
								'UpdatedBy' 	=> auth()->user()->name,
						   ];

       		$info = [ 'ObjectInstance' => $address ];
       		$response = $this->client->SaveFromAddress( $info );
			
		} 
		catch (Exception $e) 
		{
			return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );		
		}
	}

	public function getSection()
	{
	   	$section = 'All';
		/*	   	
	   	//Simple SAML user provides department attribute
	   	if ( session('login_vla_attributes') && isset( session('login_vla_attributes')['department'][0]) )
	   	{
	   		$section = session('login_vla_attributes')['department'][0];
	   	}
	   	else 
	   	{		   	*/	
   		if( auth()->user()->sp_id != 0)
		{
	   		$sp_obj = new ServiceProvider();
	   		$service_provider = $sp_obj->getServiceProviderByID( auth()->user()->sp_id );
	   		if ( $service_provider['data'] != '')
	   		{
	   			$sp_info = json_decode($service_provider['data']);
				$section =  substr( $sp_info[0]->ServiceProviderName,0,50 ); //Shouldn't be longer than 50 Chars
	   		}
   		}
	   	//}

	   	return $section;
	}    

	public function getAllLogRecords()
	{
		try 
		{
			$response = $this->client->GetAllLogRecordsasJSON( );
			$logs = json_decode( $response->GetAllLogRecordsasJSONResult, true );

			return ['data' => $logs ];
		} 
		catch (Exception $e) 
		{
			return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );		
		}
	}

    public function deleteTemplate( $te_id )
    {
        // Create call request        
        $info = [ 'RefNumber' => $te_id];

        try {
            $response = $this->client->DeleteTemplate($info);
            if($response->DeleteTemplateResult){
                return array( 'success' => 'success' , 'message' => 'NRE Template deleted.' );
            } else {
                return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        }
        catch (\Exception $e) {            
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );       
        }
    }   
}
