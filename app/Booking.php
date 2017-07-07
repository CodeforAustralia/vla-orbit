<?php
namespace App;


Class Booking
{
	public function getAllBookings( $from, $to )
	{		
		// Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_booking_init();

        $info = [
        			'FromDate'  => $from , 
    				'ToDate'	=> $to 
				];

        $bookings = json_decode($client->GetAllBookings( $info )->GetAllBookingsResult, true );

        return $bookings;
	}

	public function getAllBookingsPerMonth( $from, $to )
	{		
		// Create Soap Object
        $bookings = self::getAllBookings( $from, $to );

        $format_bookings = [];

        if( isset( $bookings['_embedded']['bookings'] ))
        {
	        foreach ( $bookings['_embedded']['bookings'] as $booking ) {
	        	$date_time = explode( 'T' , $booking['datetime'] );
	        	$time = explode( '+' , $date_time[1] );
	        	$booking['date'] = $date_time[0];
	        	$booking['time'] = $time[0];
	        	$format_bookings[] = $booking;
	        }
        }


        return ['data' => $format_bookings];
	}

	public function getAllBookingsByService( $from, $to, $sv_id )
	{
		// Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_booking_init();

        $info = [
        			'FromDate'  => $from , 
    				'ToDate' 	=> $to,
    				'ServiceId' => $sv_id
				];

        $bookings = json_decode($client->GetAllBookingsByService( $info )->GetAllBookingsByServiceResult, true );

        return $bookings;
		
	}

	public function getBookableServiesByDay( $sv_id, $from, $to )
	{
		// Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_booking_init();

        $info = [
        			'FromDate'  => $from , 
    				'ToDate' 	=> $to,
    				'ServiceId' => $sv_id
				];

        $bookings = json_decode($client->GetBookableServiesByDay( $info )->GetBookableServiesByDayResult, true );

        return $bookings;
		
	}

	public function getBookableServiesByDayWithTime( $sv_id, $from, $to )
	{
		// Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_booking_init();

        $info = [
        			'FromDate'  => $from , 
    				'ToDate' 	=> $to,
    				'ServiceId' => $sv_id
				];

        $bookings = json_decode($client->GetBookableServiesWithTime( $info )->GetBookableServiesWithTimeResult, true );

        $events = $bookings['_embedded']['events'];

        $unavailables = [];
        foreach ($events as $event) {
            if( empty( $event['times'] ) ) 
            {
                $unavailables[] = $event['date'];
            }
        }
        $bookings['unavailables'] = $unavailables;
        return $bookings;
		
	}

    public function createClient( $client_details )
    {       
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_booking_init();

        $client_details['ClientId'] = 0;

        $new_client = $client->CreateClient( $client_details )->CreateClientResult;        

        return $new_client;
    }

    public function addBookingItem( $booking )
    {       
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_booking_init();

        $reservation = $client->AddBookingItem( $booking )->AddBookingItemResult;

        return $reservation;
    }

    public function createBooking( $client_details, $booking )
    {
    	$client = self::createClient( $client_details );
    	$booking['UserEmail'] = $client->email;
    	$booking['UserMobile'] = $client->mobile;
    	$booking['ClientId'] = $client->id;
    	$reservation = self::addBookingItem( $booking );

    	return [ 'cient' => $client, 'reservation' => $reservation ];
    }

    public function deleteBooking( $booking_id )
    {	
		// Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_booking_init();

        $info = [ 
        			'BookingRef' 	=> $booking_id,
        			'CalcelReason'	=> 'Delete from admin',
        			'Nortify'		=>	false,
        			'User'			=> ' '
    			];        
        try {
        	$result = $client->DeleteBooking( $info );
            if($result->DeleteBookingResult){
            	$cancelation = json_decode($result->DeleteBookingResult, true );
                return array( 'success' => 'success' , 'message' => $cancelation['id'] . ' - ' . $cancelation['full_describe'] );
            } else {
                return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        }
        catch (\Exception $e) {            
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );           
        }
    }
}