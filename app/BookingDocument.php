<?php
namespace App;

use Auth;

/**
 * Booking model for the booking functionalities
 * @author Christian Arevalo
 * @version 1.2.0
 * @see  OrbitSoap
 */

Class BookingDocument extends OrbitSoap
{
    /**
     * Save documents attached to client bookings
     *
     * @param string    $file_path   Path to file including name
     * @param string    $client_booking_ref_no  Reference number generated by booking bug
     * @return void
     */
	public function saveBookingDocument( $file_path, $client_booking_ref_no )
	{
        $booking_doc_params = [
                                'RefNo' 			=> 0 ,
                                'Filepath'  		=> $file_path ,
                                'clientBokingRefNo'	=> $client_booking_ref_no
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
            $response = $this->client->ws_init('SaveBookingDocument')->SaveBookingDocument( $info );

            if ( $response->SaveBookingDocumentResult >= 0 ) {
                return [
                            'success' => 'success' ,
                            'message' => 'Booking document saved.',
                            'data'    => $response->SaveBookingDocumentResult
                	    ];
            } else {
                return ['success' => 'error' , 'message' => 'Ups, something went wrong.'];
            }
        }
        catch (\Exception $e) {
            return ['success' => 'error' , 'message' =>  $e->getMessage()];
        }
	}

}