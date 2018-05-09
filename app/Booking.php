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
use App\ServiceProvider;
use App\User;
use Auth;

Class Booking
{
    public function getAllBookings( $from, $to, $strict = true )
    {       
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();

        $info = [
                    'FromDate'  => $from , 
                    'ToDate'    => $to 
                ];

        $user = Auth::user();
        if( isset($user) && $user->sp_id != 0 && $strict )
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
                    '#31A5AF',
                    '#AF363E',
                    '#136EAD',
                    '#625777',
                    '#722F8E',
                    '#008000',
                    '#2F353B',
                    '#F3C200',
                    '#2C3E50',
                    '#24A3A5', 
                    '#7C7C7C',
                    '#2496A0',
                    '#2AAA91',
                    '#AD6465', 
                    '#8C8748',
                    //Repeating to avoid error on huge amount of calendars
                    '#31A500',
                    '#AF3600',
                    '#136E00',
                    '#625700',
                    '#722F00',
                    '#008011',
                    '#2F3500',
                    '#F3C211',
                    '#2C3E01',
                    '#24A300',
                    '#7C7C00',
                    '#249601',
                    '#2AAA00',
                    '#AD6400',
                    '#8C8700',
                  ];

        if( isset( $bookings->Bookings))
        {       

            if( sizeof( $bookings->Bookings ) == 1 )
            {
                $event = $bookings->Bookings ;

                $length = 30;
                if( $event->ServiceId == $event->BookingBugInternalServiceId )
                {
                    $length = $event->BookingBugInternalServiceLength;
                }
                else if( $event->ServiceId == $event->BookingBugServiceId )
                {
                    $length = $event->BookingBugServiceLength;
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
                    $length = 30;
                    if( $event->ServiceId == $event->BookingBugInternalServiceId )
                    {
                        $length = $event->BookingBugInternalServiceLength;
                    }
                    else if( $event->ServiceId == $event->BookingBugServiceId )
                    {
                        $length = $event->BookingBugServiceLength;
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

    public function updateBooking( $data )
    {
        $user = Auth::user();
        $booking_ref    = $data['booking_ref'];
        $date_time      = $data['date_time'];
        $data['ClientBooking']['UpdatedBy'] = $user->id;

        $data['ClientBooking']['IsComplex'] = ( $data['ClientBooking']['IsComplex'] == "true" ? 1 : 0);
        $data['ClientBooking']['IsSafe']    = ( $data['ClientBooking']['IsSafe'] == "true" ? 1 : 0);
        $data['ClientBooking']['IsSafeSMS'] = ( $data['ClientBooking']['IsSafeSMS'] == "true" ? 1 : 0);
        $data['ClientBooking']['IsSafeCall'] = ( $data['ClientBooking']['IsSafeCall'] == "true" ? 1 : 0);
        $data['ClientBooking']['IsSafeLeaveMessage'] = ( $data['ClientBooking']['IsSafeLeaveMessage'] == "true" ? 1 : 0);
        $data['ClientBooking']['ContactInstructions'] = ( $data['ClientBooking']['ContactInstructions'] == "" ? 'N/P' : $data['ClientBooking']['ContactInstructions']);

        $ClientBooking  = $data['ClientBooking'];
        $date_time = explode("T", $date_time);
        
        $info =     [
                            'BookingRef'=> $booking_ref ,
                            'NewDate'   => $date_time[0] ,
                            'NewTime'   => $date_time[1],
                            'ClientBooking' => $ClientBooking
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
        if( $booking['ClientBooking']['Language'] != '' )
        {
            $sp_email = $service_provider->ContactEmail;

            $subject  = 'ORBIT booking notification - ' . $booking['ClientBooking']['Language'] . 
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
                case 1: //'appointment_request':
                    $booking_request['subject'] = 'Appointment request - ';                    
                    break;
                
                case 2: //'for_assessment':
                    $booking_request['subject'] =  'For Assessment - ';               
                    break;
                
                case 3: //'phone_advice':
                    $booking_request['subject'] = 'Phone advice - ' ;
                    break;

                case 4: //'duty_layer':                
                    $booking_request['subject'] = 'Duty Lawyer - ';
                    break;

                case 5: //'child_support':                
                    $booking_request['subject'] = 'Child Support - ';
                    break;

                case 6: //'child_protection':                
                    $booking_request['subject'] = 'Child Protection - ';
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

    public function legalHelpBookings()
    {
        $legal_help_id = 112;
        $user = new User();
        $bookings = self::getAllBookings( '2017-08-01', date("Y-m-d", strtotime("+3 months")), false );
        $service_provider_obj = new ServiceProvider();
        $service_providers = $service_provider_obj->getAllServiceProviders();
        $legal_help_bookings = array();


        foreach ($bookings->Bookings as $booking)
        {
            $user_id = $booking->CreatedBy;
            $user_info = $user->find($user_id);
            if($user_info && $user_info->sp_id == $legal_help_id)
            {
                $booking->CreatedBy = $user_info->name;
                $legal_help_bookings[] = $booking;
            }
        }
        return $legal_help_bookings;
    }
}