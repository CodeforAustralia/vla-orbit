<?php
namespace App;

use Illuminate\Support\Facades\Mail;
use App\Log;
use App\Mail\BookingEmail;
use App\Mail\BookingRequestEmail;
use App\Mail\BookingSms;
use App\SentSms;
use App\Service;
use App\ServiceBooking;
use App\User;
use Auth;

Class Booking
{
    public function getAllBookings( $from, $to )
    {       
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();

        $info = [
                    'FromDate'  => $from , 
                    'ToDate'    => $to 
                ];

        $user = Auth::user();
        if( isset($user) && $user->sp_id != 0 )
        {
            $info['ServiceProviderId'] = $user->sp_id;
            $bookings = $client->GetAllOrbitBookingsByServiceProvider( $info )->GetAllOrbitBookingsByServiceProviderResult;         
        } 
        else 
        {
            $bookings = self::getFutureBookings( $from, $to );
        }

        return $bookings;
    }

	public function getFutureBookings( $from, $to )
	{		
		// Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();

        $info = [
        			'FromDate'  => $from , 
    				'ToDate'	=> $to 
				];

    	$bookings = $client->GetAllOrbitBookings( $info )->GetAllOrbitBookingsResult;
    	$temp_bookings = [];    

    	foreach ($bookings->Bookings as $booking) 
    	{
    		$booking_date = $booking->BookingDate;

    		if(($booking_date >= $from) && ($booking_date <= $to))
    		{
    			$temp_bookings[] = $booking;
    		}
    	}
    	if( sizeof( $temp_bookings ) == 1 )
    	{
    		 $temp_bookings =  $temp_bookings[0];
    	}
    	$bookings->Bookings = $temp_bookings;
		

        return $bookings;
	}

	public function getAllBookingsBySP( $from, $to, $sp_id )
	{		
		// Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();

        $info = [
        			'FromDate'  => $from , 
    				'ToDate'	=> $to ,
    				'ServiceProviderId' => $sp_id
				];

		$bookings = $client->GetAllOrbitBookingsByServiceProvider( $info )->GetAllOrbitBookingsByServiceProviderResult;			
        return $bookings;
	}

    public function getAllBookingsByDay( $day )
    {       
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();

        $info = [
                    'Date'  => $day 
                ];

        $bookings = $client->GetAllOrbitBookingsByDate( $info )->GetAllOrbitBookingsByDateResult;        

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

    public function getAllBookingsNextMonthCalendar( $from, $to, $sp_id = 0 )
    {
    	$bookings = self::getFutureBookings( $from, $to );

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

    public function getAllBookingsPerMonthCalendar( $from, $to, $sp_id = 0 )
    {       
        $user = new User();
        // Create Soap Object
        if( $sp_id != 0)
        {
        	$bookings = self::getAllBookingsBySP( $from, $to, $sp_id );
        }
        else 
        {
        	$bookings = self::getAllBookings( $from, $to );
        }
        $output = [];

        $colors = [
					'#32C5D2',
					'#E7505A',
					'#3598DC',
					'#8877A9',
					'#BF55EC',
					'#26C281',
					'#2F353B',
                    '#F3C200',
					'#2C3E50',
					'#29B4B6',
					'#BFBFBF',
					'#2AB4C0',
					'#36D7B7',
					'#E08283',
					'#C5BF66',
        		  ];

        if( isset( $bookings->Bookings))
        {
            $service_booking = new ServiceBooking();
            $all_service_bookings = $service_booking->getAllServiceBookings();            

            if( sizeof( $bookings->Bookings ) == 1 )
            {
                $event = $bookings->Bookings ;

                $pos_bs_id = array_search( $event->ServiceId,  array_column( $all_service_bookings, 'BookingServiceId' ) );
                $pos_int_bs_id = array_search( $event->ServiceId,  array_column( $all_service_bookings, 'InternalBookingServId' ) );

                $length = 30;
                if( $pos_bs_id )
                {
                    $length = $all_service_bookings[ $pos_bs_id ]['ServiceLength'];
                }
                else if( $pos_int_bs_id )
                {
                    $length = $all_service_bookings[ $pos_int_bs_id ]['IntServiceLength'];
                }

                $time = strtotime( $event->BookingTime );
				$endTime = date("H:i", strtotime('+' . $length . ' minutes', $time));

                $uid = $bookings->Bookings->CreatedBy;                
  
                $output[] =  [
                                'title' => $event->FirstName .  ' ' . $event->LastName . ' - ' . $event->ServiceName ,
                                'start' => $event->BookingDate . ' ' . $event->BookingTime,
                                'end' => $event->BookingDate . ' ' . $endTime,
                                'backgroundColor' => '#17C4BB',
                                'allDay' => false,
                                'data'   => $event,
                                'user'   => $user->find($uid)
                              ];
            } 
            elseif( sizeof( $bookings->Bookings ) > 1 ) 
            {
            	$calendars = [];
                foreach ( $bookings->Bookings as $event ) 
                {

                    $pos_bs_id = array_search( $event->ServiceId,  array_column( $all_service_bookings, 'BookingServiceId' ) );
                    $pos_int_bs_id = array_search( $event->ServiceId,  array_column( $all_service_bookings, 'InternalBookingServId' ) );

                    $length = 30;
                    if( $pos_bs_id )
                    {
                        $length = $all_service_bookings[ $pos_bs_id ]['ServiceLength'];
                    }
                    else if( $pos_int_bs_id )
                    {
                        $length = $all_service_bookings[ $pos_int_bs_id ]['IntServiceLength'];
                    }

                    $time = strtotime( $event->BookingTime );					
                    $endTime = date("H:i", strtotime('+' . $length . ' minutes', $time));

					if( !in_array( $event->ServiceName, $calendars) )
					{
						$calendars[] = $event->ServiceName;
					}

					$calendar_pos = array_search($event->ServiceName, $calendars);

                    $uid = $event->CreatedBy;   

                    $output[] =   [
                                    'title' => $event->FirstName .  ' ' . $event->LastName . ' - ' . $event->ServiceName ,
                                    'start' => $event->BookingDate . ' ' . $event->BookingTime,
                                	'end' => $event->BookingDate . ' ' . $endTime,
                                    'backgroundColor' => $colors[ $calendar_pos ],
                                    'allDay' => false,
                                    'data'   => $event,
                                    'user'   => $user->find($uid)
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

    public function getBookingsByUser()
    {       
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();
        $bookings = [];
        $user = Auth::user();
        if( $user->id != 0 )
        {
            $info['UserID'] = $user->id;
            $bookings = json_decode( $client->GetAllOrbitBookingsByUserasJSON( $info )->GetAllOrbitBookingsByUserasJSONResult, true );
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

    public function createBooking( $client_details, $booking, $service_provider, $service_name )
    {
        $SendNotification = false;
        $user = Auth::user();
        $client = self::createClient( $client_details );
        $booking['AdminUserId']      = $user->id;
        $booking['SendNotification'] = $SendNotification;

        $UserObject =   [
                            'LocalRef'  => $client->LocalRef ,
                            'country'   => $client->country ,
                            'email'     => $client->email ,
                            'first_name' => $client->first_name ,
                            'id'        => $client->id ,
                            'last_name' => $client->last_name ,
                            'mobile'    => $client->mobile ,
                        ];
        $booking['UserObject'] = $UserObject;
        $reservation = self::createBookingService( $booking );

        self::notifyBooking( $booking, $client_details, $service_provider, $service_name );
        $reservation_details = json_decode( $reservation );

        $log = new Log();
        $log::record('CREATE', 'booking', $reservation_details->id, $booking);
        
        return [ 'cient' => $client, 'reservation' => $reservation ];
    }

    public function updateBooking( $booking_ref, $date_time )
    {
        $date_time = explode("T", $date_time);

        $info =     [
                            'BookingRef'=> $booking_ref ,
                            'NewDate'   => $date_time[0] ,
                            'NewTime'   => $date_time[1]
                        ];
        
        $client =  (new \App\Repositories\VlaSoap)->ws_booking_init();

        $result = $client->UpdateBooking( $info )->UpdateBookingResult;
        
        $log = new Log();
        $log::record('UPDATE', 'booking', $booking_ref, $info);

        return $result;
    }

    public function updateBookingDetails( $booking )
    {
        //This WS belongs to the basic behabour of Orbit and not to the booking services
        $client =  (new \App\Repositories\VlaSoap)->ws_init();

    	$info = 	[ 'ObjectInstance'=> $booking ];        
        $result = $client->SaveOrbitLocalBooking( $info )->SaveOrbitLocalBookingResult;
        
        $log = new Log();
        $log::record('UPDATE', 'booking', $booking->BookingRef, $booking);

    	return $result;
    }

    public function deleteBooking( $booking_id )
    {	
		// Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_booking_init();

        $user = Auth::user();

        $info = [ 
        			'BookingRef' 	=> $booking_id,
        			'CalcelReason'	=> 'Canceled from admin',
        			'Nortify'		=>	false,
        			'User'			=> $user->id
    			];        
        try {
        	$result = $client->DeleteBooking( $info );
            if($result->DeleteBookingResult){
            	$cancelation = json_decode($result->DeleteBookingResult, true );

                $log = new Log();
                $log::record('DELETE', 'booking', $booking_id, $info);

                return array( 'success' => 'success' , 'message' => $cancelation['id'] . ' - ' . $cancelation['full_describe'] );
            } else {
                return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        }
        catch (\Exception $e) {            
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );           
        }
    }

    public function notifyBooking( $booking, $client, $service_provider, $service_name )
    {
        $user = Auth::user();

        // If an user belongs to Legal Help send email to VLA Referrals contact email
        if( $user->sp_id == '112' )
        {
            $lh_email = 'LHReferrals@vla.vic.gov.au';

            $subject  = 'ORBIT booking by ' . $user->name . ' - ' . $service_provider->ServiceProviderName . ', ' . $client['FirstName'] . ' ' . $client['LastName'];

            $args = [
                        'subject' => $subject,
                        'client'  => $client,
                        'booking' => $booking,
                        'service_name' => $service_name,
                        'send_booking' => 1
                    ];

            Mail::to( $lh_email )->send( new BookingEmail( $args ) );

        }

        //If an interpreter required in a booking, send a copy of the booking details to the Service email address
        if( $booking['Language'] != '' )
        {
            $sp_email = $service_provider->ContactEmail;

            $subject  = 'ORBIT booking notification - ' . $booking['Language'] . 
                        ' interpreter required on ' . $booking['Date'] . ' ' . $booking['Time'];

            $args = [
                        'subject' => $subject,
                        'send_booking' => 0
                    ];

            Mail::to( $sp_email )->send( new BookingEmail( $args ) );
        }       
    }

    public function requestBooking( $booking_request )
    {
        $sv_id = explode('-',$booking_request['ServiceId'])[2];
        
        $service_obj = new Service();        
        $service = json_decode( $service_obj->getServiceByID($sv_id)['data'] )[0];
        
        if( isset($service->Email) && $service->Email != '' )
        {
            $sp_email = $service->Email;            
            switch ( $booking_request["request_type"] ) 
            {
                case 'appointment_request':
                    $booking_request['subject'] = 'Appointment request - ';                    
                    break;
                
                case 'for_assessment':
                    $booking_request['subject'] =  'For Assessment - ';               
                    break;
                
                case 'phone_advice':
                    $booking_request['subject'] = 'Phone advice - ' ;
                    break;

                case 'duty_layer':                
                    $booking_request['subject'] = 'Duty Lawyer - ';
                    break;
            }

            $booking_request['subject'] .=  $booking_request['ServiceProviderName'] . ', ' . 
                                            $booking_request['ServiceName'] . ': ' . 
                                            $booking_request['client']['LastName'] . ', ' . 
                                            $booking_request['client']['FirstName'] . ' - ' . 
                                            (   
                                                isset($booking_request['client']['Mobile']) ? 
                                                $booking_request['client']['Mobile'] : ''
                                            );
                    
            $log = new Log();
            $log::record('CREATE', 'booking_request', 0, $booking_request);
            Mail::to( $sp_email )->send( new BookingRequestEmail( $booking_request ) );
            return true;
        } 
        else
        {
            return false;
        }
    }
}