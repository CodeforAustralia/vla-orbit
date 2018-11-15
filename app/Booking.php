<?php
namespace App;

use Illuminate\Support\Facades\Mail;
use \App\Repositories\BookingEngineApi;
use Exception;
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

/**
 * Booking model for the booking functionalities
 * @author Christian Arevalo
 * @version 1.0.0
 * @see  OrbitSoap
 */

const APPOINTMENT_REQUEST = 1;
const FOR_ASSESSMENT = 2;
const PHONE_ADVICE = 3;
const DUTY_LAYER = 4;
const CHILD_SUPPORT = 5;
const CHILD_PROTECTION = 6;
const LEGAL_HELP_ID = 112;
const LEGAL_HELP_EMAIL = 'LHReferrals@vla.vic.gov.au';

Class Booking extends OrbitSoap
{
    /**
     * Get a list of all bookings between two dates
     * @param  date    $from   intial date
     * @param  date    $to     final date
     * @param  boolean $strict
     * @return array           List of bookings
     */
    public function getAllBookings( $from, $to, $strict = true )
    {
        $info = [
                    'FromDate'  => $from ,
                    'ToDate'    => $to
                ];

        $user = Auth::user();
        if ( isset($user) && $user->sp_id != 0 && $strict ) {

            $info['ServiceProviderId'] = $user->sp_id;
            $bookings = $this
                        ->client
                        ->ws_init( 'GetAllOrbitBookingsByServiceProvider' )
                        ->GetAllOrbitBookingsByServiceProvider( $info )
                        ->GetAllOrbitBookingsByServiceProviderResult;
        } else {

            $bookings = self::getFutureBookings( $from, $to );
        }

        return $bookings;
    }
    /**
     * Get all bookings between two dates
     * @param  date   $from     from date
     * @param  date   $to       to date
     * @return array  $bookings bookings array
     */
	public function getFutureBookings( $from, $to )
	{

        $info = [
        			'FromDate'  => $from ,
    				'ToDate'	=> $to
				];

        $bookings = $this
                    ->client
                    ->ws_init( 'GetAllOrbitBookings' )
                    ->GetAllOrbitBookings( $info )
                    ->GetAllOrbitBookingsResult;
    	$temp_bookings = [];

    	foreach ($bookings->Bookings as $booking) {

    		$booking_date = $booking->BookingDate;

    		if ( ( $booking_date >= $from ) && ( $booking_date <= $to ) ) {
    			$temp_bookings[] = $booking;
    		}
    	}
    	if ( sizeof( $temp_bookings ) == 1 ) {
    		 $temp_bookings =  $temp_bookings[0];
    	}
    	$bookings->Bookings = $temp_bookings;

        return $bookings;
	}
    /**
     * Get all bookings between two dates and by service provider
     * @param  date   $from     from date
     * @param  date   $to       to date
     * @param  int    $sp_id    service provider id
     * @return array  $bookings bookings array
     */
	public function getAllBookingsBySP( $from, $to, $sp_id )
	{
        $info = [
        			'FromDate'  => $from ,
    				'ToDate'	=> $to ,
    				'ServiceProviderId' => $sp_id
				];

        $bookings = $this
                    ->client
                    ->ws_init( 'GetAllOrbitBookingsByServiceProvider' )
                    ->GetAllOrbitBookingsByServiceProvider( $info )
                    ->GetAllOrbitBookingsByServiceProviderResult;
        return $bookings;
	}
    /**
     * Get all bookings by day
     * @param  date   $day
     * @return array  $bookings bookings array
     * @deprecated
     */
    public function getAllBookingsByDay( $day )
    {
        $info = [
                    'Date'  => $day
                ];

        $bookings = $this
                    ->client
                    ->ws_init( 'GetAllOrbitBookingsByDate' )
                    ->GetAllOrbitBookingsByDate( $info )
                    ->GetAllOrbitBookingsByDateResult;

        return $bookings;
    }
    /**
     * Get all bookings per month
     * @param  date   $from     from date
     * @param  date   $to       to date
     * @return array  $bookings bookings array
     */
    public function getAllBookingsPerMonth( $from, $to )
    {
        // Create Soap Object
        $bookings = self::getAllBookings( $from, $to );

        if ( isset( $bookings->Bookings ) ) {
            if ( sizeof( $bookings->Bookings ) == 1 ) {
                $bookings->Bookings = [ $bookings->Bookings ];
                return [ 'data' => $bookings->Bookings ];
            } else {
                return [ 'data' => $bookings->Bookings ];
            }

        }
        return ['data' => ''];
    }
    /**
     * Get all bookings for the next month calendar
     * @param  date   $from     from date
     * @param  date   $to       to date
     * @param  int    $sp_id    service provider id
     * @return array  $bookings bookings array
     */
    public function getAllBookingsNextMonthCalendar( $from, $to, $sp_id = 0 )
    {
    	$bookings = self::getFutureBookings( $from, $to );

        if ( isset( $bookings->Bookings ) ) {
            if ( sizeof( $bookings->Bookings ) == 1 ) {
                $bookings->Bookings = [ $bookings->Bookings ];
                return [ 'data' => $bookings->Bookings ];
            } else {
                return [ 'data' => $bookings->Bookings ];
            }

        }
        return ['data' => ''];
    }
    /**
     * Get all bookings for a specific month calendar in a html configuration
     * @param  date   $from     from date
     * @param  date   $to       to date
     * @param  int    $sp_id    service provider id
     * @return array  $output   Booking html specification
     */
    public function getAllBookingsPerMonthCalendar( $from, $to, $sp_id = 0 )
    {
        $user = new User();

        if ( $sp_id != 0 ) {
        	$bookings = self::getAllBookingsBySP( $from, $to, $sp_id );
        } else {
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

        if ( isset( $bookings->Bookings ) ) {

            if ( sizeof( $bookings->Bookings ) == 1 ) {

                $event = $bookings->Bookings ;

                $length = 30;
                if ( $event->ServiceId == $event->BookingBugInternalServiceId ) {
                    $length = $event->BookingBugInternalServiceLength;
                } elseif ( $event->ServiceId == $event->BookingBugServiceId ) {
                    $length = $event->BookingBugServiceLength;
                }

                $time = strtotime( $event->BookingTime );
				$endTime = date("H:i", strtotime( '+' . $length . ' minutes', $time ) );

                $uid = $bookings->Bookings->CreatedBy;

                $output[] =  [
                                'title'             => $event->FirstName .  ' ' . $event->LastName . ' - ' . $event->ServiceName ,
                                'start'             => $event->BookingDate . ' ' . $event->BookingTime ,
                                'end'               => $event->BookingDate . ' ' . $endTime ,
                                'backgroundColor'   => '#17C4BB' ,
                                'allDay'            => false ,
                                'data'              => $event ,
                                'user'              => $user->find( $uid )
                              ];
            } elseif ( sizeof( $bookings->Bookings ) > 1 ) {

                $calendars = [];
                foreach ( $bookings->Bookings as $event ) {
                    $length = 30;
                    if ( $event->ServiceId == $event->BookingBugInternalServiceId ) {
                        $length = $event->BookingBugInternalServiceLength;
                    } elseif ( $event->ServiceId == $event->BookingBugServiceId ) {
                        $length = $event->BookingBugServiceLength;
                    }

                    $time = strtotime( $event->BookingTime );
                    $endTime = date( "H:i", strtotime('+' . $length . ' minutes', $time ) );

					if ( !in_array( $event->ServiceName, $calendars ) ) {
						$calendars[] = $event->ServiceName;
					}

					$calendar_pos = array_search( $event->ServiceName, $calendars );

                    $uid = $event->CreatedBy;

                    $output[] = [
                                    'title'             => $event->FirstName .  ' ' . $event->LastName . ' - ' . $event->ServiceName ,
                                    'start'             => $event->BookingDate . ' ' . $event->BookingTime ,
                                	'end'               => $event->BookingDate . ' ' . $endTime,
                                    'backgroundColor'   => $colors[ $calendar_pos ] ,
                                    'allDay'            => false ,
                                    'data'              => $event ,
                                    'user'              => $user->find( $uid )
                                ];
                }
            }
        }

        return $output;
    }
    /**
     * Get all bookings by service
     * @param  date   $from     from date
     * @param  date   $to       to date
     * @param  int    $sv_id    service id
     * @return array  $bookings bookings array
     */
	public function getAllBookingsByService( $from, $to, $sv_id )
	{
        $info = [
        			'FromDate'  => $from ,
    				'ToDate' 	=> $to ,
    				'ServiceId' => $sv_id
				];

        $bookings = json_decode(
                                    $this
                                    ->client
                                    ->ws_booking_init( 'GetAllBookingsByService' )
                                    ->GetAllBookingsByService( $info )
                                    ->GetAllBookingsByServiceResult
                                    , true
                                );

        return $bookings;

	}
    /**
     * Get all bookable services by day
     * @param  int    $sv_id    service id
     * @param  date   $from     from date
     * @param  date   $to       to date
     * @return array  $bookings bookings array
     */
	public function getBookableServiesByDay( $sv_id, $from, $to )
	{
        $info = [
        			'FromDate'  => $from ,
    				'ToDate' 	=> $to ,
    				'ServiceId' => $sv_id
				];

        $bookings = json_decode(
                                    $this
                                    ->client
                                    ->ws_booking_init( 'GetBookableServiesByDay' )
                                    ->GetBookableServiesByDay( $info )
                                    ->GetBookableServiesByDayResult
                                    , true
                                );

        return $bookings;

    }

    /**
     * Get all bookable services by day
     * @param  int    $sv_id    service id
     * @param  date   $from     from date
     * @param  date   $to       to date
     * @return array  $bookings bookings array with time
     */
	public function getBookableServiesByDayWithTime( $sv_id, $from, $to )
	{
        $info = [
        			'FromDate'  => $from ,
    				'ToDate' 	=> $to ,
    				'ServiceId' => $sv_id
				];

         $bookings = json_decode(
                                    $this
                                    ->client
                                    ->ws_booking_init( 'GetBookableServiesWithTime' )
                                    ->GetBookableServiesWithTime( $info )
                                    ->GetBookableServiesWithTimeResult
                                    , true
                                );

        if ($bookings) {
            $events = $bookings[ '_embedded' ][ 'events' ];

            $unavailables = [];
            foreach ( $events as $event ) {
                if ( empty( $event[ 'times' ] ) ) {
                    $unavailables[] = $event[ 'date' ];
                }
            }
            $bookings[ 'unavailables' ] = $unavailables;
        } else {
            $bookings = [];
        }
        return $bookings;

    }

    /**
     * Get all booking by user
     * @return array  $bookings bookings array
     */
    public function getBookingsByUser()
    {
        $bookings = [];
        $user = Auth::user();
        if ( $user->id != 0 ) {
            $info[ 'UserID' ] = $user->id;
            $bookings = json_decode(
                                        $this
                                        ->client
                                        ->ws_init( 'GetAllOrbitBookingsByUserasJSON' )
                                        ->GetAllOrbitBookingsByUserasJSON( $info )
                                        ->GetAllOrbitBookingsByUserasJSONResult,
                                        true
                                    );
        }

        return $bookings;
    }

    /**
     * Create new client for Booking Bug
     * @param  array    $client_details Client details
     * @return          $new_client     The new client object
     */
    public function createClient( $client_details )
    {
        $client_details[ 'ClientId' ] = 0;

        $new_client = $this
                      ->client
                      ->ws_booking_init( 'CreateClient' )
                      ->CreateClient( $client_details )
                      ->CreateClientResult;

        return $new_client;
    }

    /**
     * Add a booking item in Booking Bug
     * @param  array  $booking     booking bug item
     * @return Object $reservation booking item
     */
    public function addBookingItem( $booking )
    {
        $reservation = $this
                      ->client
                      ->ws_booking_init( 'AddBookingItem' )
                      ->AddBookingItem( $booking )
                      ->AddBookingItemResult;

        return $reservation;
    }

    /**
     * Create booking bug service
     * @param  Object $booking      booking details
     * @return Object $reservation  new booking reservation
     */
    public function createBookingService( $booking )
    {
        $reservation = $this
                      ->client
                      ->ws_booking_init('CreateBooking')
                      ->CreateBooking( $booking )
                      ->CreateBookingResult;

        return $reservation;
    }


    /**
     * Send the booking information to the Booking Engine
     *
     * @param array $booking
     * @return void
     */
    public function storeBooking($booking, $service_provider, $service_name)
    {
        try{
            self::notifyBooking( $booking, $service_provider, $service_name );
            return $data;

        }catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * Update Booking bug service
     * @param  array  $data     update details
     * @return Object $result   update result
     */
    public function updateBooking( $data )
    {
        $user = Auth::user();
        $booking_ref = $data[ 'booking_ref' ];
        $date_time   = $data[ 'date_time' ];
        $data[ 'ClientBooking' ][ 'UpdatedBy' ] = $user->id;

        $data['ClientBooking']['IsComplex'] = ( $data['ClientBooking']['IsComplex'] == "true" ? 1 : 0);
        $data['ClientBooking']['IsSafe']    = ( $data['ClientBooking']['IsSafe'] == "true" ? 1 : 0);
        $data['ClientBooking']['IsSafeSMS'] = ( $data['ClientBooking']['IsSafeSMS'] == "true" ? 1 : 0);
        $data['ClientBooking']['IsSafeCall'] = ( $data['ClientBooking']['IsSafeCall'] == "true" ? 1 : 0);
        $data['ClientBooking']['IsSafeLeaveMessage'] = ( $data['ClientBooking']['IsSafeLeaveMessage'] == "true" ? 1 : 0);
        $data['ClientBooking']['ContactInstructions'] = ( $data['ClientBooking']['ContactInstructions'] == "" ? 'N/P' : $data['ClientBooking']['ContactInstructions']);

        $ClientBooking  = $data[ 'ClientBooking' ];
        $date_time = explode( "T", $date_time );

        $info = [
                    'BookingRef'=> $booking_ref ,
                    'NewDate'   => $date_time[ 0 ] ,
                    'NewTime'   => $date_time[ 1 ],
                    'ClientBooking' => $ClientBooking
                ];

        $result = $this
                  ->client
                  ->ws_booking_init( 'UpdateBooking' )
                  ->UpdateBooking( $info )
                  ->UpdateBookingResult;

        $log = new Log();
        $log::record( 'UPDATE', 'booking', $booking_ref, $info );

        return $result;
    }

    /**
     * Update boooking details
     * @param  array $booking   booking details
     * @return       $result    booking result
     */
    public function updateBookingDetails( $booking )
    {
    	$info = 	[ 'ObjectInstance'=> $booking ];
        $result = $this
                  ->client
                  ->ws_init( 'SaveOrbitLocalBooking' )
                  ->SaveOrbitLocalBooking( $info )->SaveOrbitLocalBookingResult;

        $log = new Log();
        $log::record( 'UPDATE', 'booking', $booking->BookingRef, $booking );

    	return $result;
    }

    /**
     * Delete booking
     * @param  int  $booking_id booking id
     * @return array            succes status and message
     */
    public function deleteBooking( $booking_id )
    {
        $user = Auth::user();

        $info = [
        			'BookingRef' 	=> $booking_id,
        			'CalcelReason'	=> 'Canceled from admin',
        			'Nortify'		=>	false,
        			'User'			=> $user->id
    			];
        try {
            $result = $this
                      ->client
                      ->ws_booking_init( 'DeleteBooking' )
                      ->DeleteBooking( $info );

            if ( $result->DeleteBookingResult ) {
            	$cancelation = json_decode( $result->DeleteBookingResult, true );

                $log = new Log();
                $log::record( 'DELETE', 'booking', $booking_id, $info );

                return [ 'success' => 'success' , 'message' => $cancelation['id'] . ' - ' . $cancelation['full_describe'] ];
            } else {
                return [ 'success' => 'error' , 'message' => 'Ups, something went wrong.' ];
            }
        }
        catch ( \Exception $e ) {
            return [ 'success' => 'error' , 'message' =>  $e->getMessage() ];
        }
    }


    /**
     * Notify a booking to Legal Help via email and SMS
     * @param  array  $booking          booking details
     * @param  array  $client           client details
     * @param  Object $service_provider service provider details
     * @param  String $service_name     service name
     */
    public function notifyBooking( $booking, $service_provider, $service_name )
    {
        $user = Auth::user();

        // If an user belongs to Legal Help send email to VLA Referrals contact email
        if ( $user->sp_id == LEGAL_HELP_ID ) {
            $lh_email = LEGAL_HELP_EMAIL;

            $subject  = strtoupper(config('app.name')) . ' booking by ' . $user->name . ' - ' .
                        $service_provider->ServiceProviderName . ', ' . $booking['first_name'] .
                        ' ' . $booking['last_name'];

            $args = [
                        'subject' => $subject,
                        'booking' => $booking,
                        'service_name' => $service_name,
                        'send_booking' => 1
                    ];

            Mail::to( $lh_email )->send( new BookingEmail( $args ) );

        }

        //If an interpreter required in a booking, send a copy of the booking details to the Service email address
        if ( $booking[ 'int_language' ] != '' ) {
            $sp_email = $service_provider->ContactEmail;

            $subject  = strtoupper(config('app.name')) . ' booking notification - ' . $booking['int_language'] .
                        ' interpreter required on ' . $booking['date'] . ' ' . $booking['hour'];

            $args = [
                        'subject' => $subject,
                        'send_booking' => 0
                    ];

            Mail::to( $sp_email )->send( new BookingEmail( $args ) );
        }

        if ( $booking['RemindNow'] == 1 && $booking['contact'] != '' ) {

            $args = [
                        'FirstName'     => $booking['first_name'] . ' ' . $booking['last_name'],
                        'Mobile'        => $booking['contact'] ,
                        'BookingDate'   => $booking['date'],
                        'BookingTime'   => $booking['hour'],
                        'ServiceId'     => $booking['service_id'],
                        'RefNo'         => $booking['booking_no'],
                        'IsSafeSMS'     => 1
                    ];
            $sent_sms_obj = New SentSms();
            $sent_sms_obj->sendReminder( (object) $args );

            $log = new Log();
            $log::record( 'CREATE', 'remind_booking', 0, $args );
        }
    }

    /**
     * Send booking request by email
     * @param  array  $booking_request booking request details
     * @return boolean                 true if success false otherwise
     */
    public function requestBooking( $booking_request )
    {
        $sv_id = explode( '-', $booking_request['ServiceId'] )[2];

        $service_obj = new Service();
        $service = json_decode( $service_obj->getServiceByID($sv_id)['data'] )[0];

        if ( isset($service->Email) && $service->Email != '' ) {
            $sp_email = $service->Email;
            switch ( $booking_request["request_type"] ) {
                case APPOINTMENT_REQUEST:
                    $booking_request['subject'] = 'Appointment request - ';
                    break;

                case FOR_ASSESSMENT:
                    $booking_request['subject'] =  'For Assessment - ';
                    break;

                case PHONE_ADVICE:
                    $booking_request['subject'] = 'Phone advice - ' ;
                    break;

                case DUTY_LAYER:
                    $booking_request['subject'] = 'Duty Lawyer - ';
                    break;

                case CHILD_SUPPORT:
                    $booking_request['subject'] = 'Child Support - ';
                    break;

                case CHILD_PROTECTION:
                    $booking_request['subject'] = 'Child Protection - ';
                    break;
            }

            $booking_request['subject'] .=  $booking_request['ServiceProviderName'] . ', ' .
                                            $booking_request['ServiceName'] . ': ' .
                                            $booking_request['lastName'] . ', ' .
                                            $booking_request['firstName'] . ' - ' .
                                            (
                                                isset($booking_request['phone']) ?
                                                $booking_request['phone'] : ''
                                            );

            $log = new Log();
            $log::record( 'CREATE', 'booking_request', 0, $booking_request );
            Mail::to( $sp_email )->send( new BookingRequestEmail( $booking_request ) );
            return true;
        } else {
            return false;
        }
    }
    /**
     * Get Legal Help Bookings
     * @return array legal help bookings
     */
    public function legalHelpBookings()
    {
        $legal_help_id = LEGAL_HELP_ID;
        $user = new User();
        $bookings = self::getAllBookings( '2017-08-01', date( "Y-m-d", strtotime( "+3 months" ) ), false );
        $service_provider_obj = new ServiceProvider();
        $service_providers = $service_provider_obj->getAllServiceProviders();
        $legal_help_bookings = [];


        foreach ($bookings->Bookings as $booking) {
            $user_id = $booking->CreatedBy;
            $user_info = $user->find($user_id);
            if ( $user_info && $user_info->sp_id == $legal_help_id ) {
                $booking->CreatedBy = $user_info->name;
                $legal_help_bookings[] = $booking;
            }
        }
        return $legal_help_bookings;
    }
}