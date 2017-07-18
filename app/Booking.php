<?php
namespace App;

use Auth;

Class Booking
{
	public function getAllBookings( $from, $to )
	{		
		// Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();

        $info = [
        			'FromDate'  => $from , 
    				'ToDate'	=> $to 
				];

		$user = Auth::user();
		if( $user->sp_id != 0 )
		{
			$info['ServiceProviderId'] = $user->sp_id;
			$bookings = $client->GetAllOrbitBookingsByServiceProvider( $info )->GetAllOrbitBookingsByServiceProviderResult;			
		} else {
        	$bookings = $client->GetAllOrbitBookings( $info )->GetAllOrbitBookingsResult;
		}

        return $bookings;
	}

    public function getAllBookingsPerMonth( $from, $to )
    {       
        // Create Soap Object
        $bookings = self::getAllBookings( $from, $to );

        if( isset( $bookings->Bookings))
        {
            if( sizeof( $bookings->Bookings ) == 1 )
            {
                $bookings->Bookings = [ $bookings->Bookings ];
                return [ 'data' => $bookings->Bookings ];
            } else {
                return [ 'data' => $bookings->Bookings ];
            }

        }
        return ['data' => ''];
    }

    public function getAllBookingsPerMonthCalendar( $from, $to )
    {       
        // Create Soap Object
        $bookings = self::getAllBookings( $from, $to );
        $output = [];
        if( isset( $bookings->Bookings))
        {
            if( sizeof( $bookings->Bookings ) == 1 )
            {
                $event = $bookings->Bookings;
                $output[] =  [
                                'title' => $event->FirstName .  ' ' . $event->LastName . ' - ' . $event->ServiceName ,
                                'start' => $event->BookingDate . ' ' . $event->BookingTime,
                                'backgroundColor' => '#17C4BB',
                                'allDay' => false,
                                'data'   => $event
                              ];
            } 
            elseif( sizeof( $bookings->Bookings ) > 1 ) 
            {
                foreach ( $bookings->Bookings as $event ) 
                {
                    $output[] =   [
                                    'title' => $event->FirstName .  ' ' . $event->LastName . ' - ' . $event->ServiceName ,
                                    'start' => $event->BookingDate . ' ' . $event->BookingTime,
                                    'backgroundColor' => '#17C4BB',
                                    'allDay' => false,
                                    'data'   => $event
                                  ];
                }
            }
        }

        return $output;
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

        if ($bookings)
        {
            $events = $bookings['_embedded']['events'];

            $unavailables = [];
            foreach ($events as $event) {
                if( empty( $event['times'] ) ) 
                {
                    $unavailables[] = $event['date'];
                }
            }
            $bookings['unavailables'] = $unavailables;            
        }
        else {
            $bookings = [];
        }
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

    public function createBookingService( $booking )
    {       
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_booking_init();

        $reservation = $client->CreateBooking( $booking )->CreateBookingResult;

        return $reservation;
    }

    public function createBooking( $client_details, $booking )
    {
    	$SendNotification = false;
        $user = Auth::user();
    	$client = self::createClient( $client_details );
    	$booking['AdminUserId'] 	 = $user->id;
    	$booking['SendNotification'] = $SendNotification;

    	$UserObject = 	[
							'LocalRef' 	=> $client->LocalRef ,
							'country' 	=> $client->country ,
							'email' 	=> $client->email ,
							'first_name' => $client->first_name ,
							'id' 		=> $client->id ,
							'last_name' => $client->last_name ,
							'mobile' 	=> $client->mobile ,
    					];
    	$booking['UserObject'] = $UserObject;
    	$reservation = self::createBookingService( $booking );
        
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