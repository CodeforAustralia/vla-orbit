<?php
namespace App;

use Auth;

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
			dd(session('login_vla_attributes'));
			$response = $this->client->GetAllTemplatesasJSON();
			return json_decode( $response->GetAllTemplatesasJSONResult, true );
		} 
		catch (Exception $e) 
		{
			
		}
	}

	public function getAllTemplatesBySection( $section )
	{
		try 
		{
			$info = [ 'Section' => $section ];
			$response = $this->client->GetTemplatesBySectionasJSON( $info );
			return json_decode( $response->GetTemplatesBySectionasJSONResult, true );
		} 
		catch (Exception $e) 
		{
			
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
			
		}
	}

	public function sendEmail()
	{	
		try 
		{
	        // Current time
	        $date_now  = date("Y-m-d");
	        $time_now  = date("H:i:s");
	        $date_time = $date_now . "T" . $time_now;

	        $location = "/var/www/html/public/ch/files/test.pdf"; // Mention where to upload the file
	        
			$handle = fopen($location, "rb");                  // Open the temp file

	       	$content = fread($handle, filesize($location) );  // Read the temp file    		
	       	fclose($handle);                                 // Close the temp file
	       	$out = $content;
	       	$attachments[] = [ 'AttachmentBytes' => $out, 'FileName' => 'test.pdf'];

			$info = [
						'MessageObject' => [
												'Attachments' 	=> $attachments,
												'Body' 			=> '<h1>hola</h1>',
												//'Body' 			=> 'Hola',
												'Deliverd' 		=> 1,
												'Error' 		=> 0,
												'FromAddress' 	=> 'noreply@vla.vic.gov.au',
												'PersonID' 		=> 'ChristianA16',
												'RefNo' 		=> 0,
												'Section' 		=> 'Applications',
												'SentOn' 		=> $date_time,
												'Subject' 		=> 'Testing 10:04',
												'ToAddress' 	=> 'christian@codeforaustralia.org',
											],
						'IsHTML'		=> true,
					];
			
			$response = $this->client->SendEmailasJSON($info);
			
			return json_decode( $response->SendEmailasJSONResult, true );
		} 
		catch (Exception $e) 
		{
			return "Error";
		}
	}

	public function saveEmailTemplate(  )
	{
		try {
	        // Current time
	        $date_now  = date("Y-m-d");
	        $time_now  = date("H:i:s");
	        $date_time = $date_now . "T" . $time_now;
/*
			$template =  [
								'RefNo' 		=> 0,
								'Created' 		=> $date_time,
								'CreatedBy' 	=> auth()->user()->id,
								'Name' 			=> $data['name'],
								'Section' 		=> $data['section'],
								'TemplateText' 	=> $data['template'],
								'Updated' 		=> $date_time,
								'UpdatedBy' 	=> auth()->user()->id,
						   ];*/
			$template =  [
								'RefNo' 		=> 0,
								'Created' 		=> $date_time,
								'CreatedBy' 	=> auth()->user()->id,
								'Name' 			=> 'CFA test',
								'Section' 		=> 'All',
								'TemplateText' 	=> '<h1>Hola</h1>',
								'Updated' 		=> $date_time,
								'UpdatedBy' 	=> auth()->user()->id,
						   ];

       		$info = [ 'ObjectInstance' => $template ];
       		$response = $this->client->SaveEmailTemplate( $info );
			
		} catch (Exception $e) {
			
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
			
		} catch (Exception $e) {
			
		}
	}
    

}
