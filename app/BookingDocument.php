<?php
namespace App;

use Auth;

Class BookingDocument
{
	public $client;

	function __construct() 
	{
	       $this->client = (new \App\Repositories\VlaSoap)->ws_init();
	}

	public function saveBookingDocument( $filePath, $clientBokingRefNo )
	{
        $booking_doc_params = [
				        			'RefNo' 			=> 0 , 
				        			'Filepath'  		=> $filePath , 
				    				'clientBokingRefNo'	=> $clientBokingRefNo 
							  ];

		// Current time
        $date_now = date("Y-m-d");
        $time_now = date("H:i:s");
        $date_time = $date_now . "T" . $time_now;

        $booking_doc_params['CreatedBy'] = auth()->user()->name;
        $booking_doc_params['UpdatedBy'] = auth()->user()->name;
        $booking_doc_params['CreatedOn'] = $date_time;
        $booking_doc_params['UpdatedOn'] = $date_time;

        $info = [ 'ObjectInstance' => $booking_doc_params ];

        try {
            $response = $this->client->SaveBookingDocument( $info );            
            // Redirect to index        
            if( $response->SaveBookingDocumentResult >= 0 ){
                return array( 
                				'success' => 'success' , 
                				'message' => 'Booking document saved.', 
                				'data' => $response->SaveBookingDocumentResult 
                			);
            } else {
                return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        }
        catch (\Exception $e) {            
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );       
        }
	}

}