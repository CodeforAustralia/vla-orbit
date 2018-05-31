<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use File;
use App\Booking;
use App\BookingDocument;
use App\SentSms;
use App\ServiceProvider;
use Auth;

class BookingController extends Controller
{

    public function __construct()
    {       
        $this->middleware('auth');
    }
    
    public function index()
    {
        Auth::user()->authorizeRoles( ['Administrator', 'AdminSp' , 'VLA']);       
        return view("booking.index");
    }
    
    public function nextBookings()
    {
        Auth::user()->authorizeRoles( ['Administrator', 'AdminSp' , 'VLA']);
        return view("booking.next_bookings");
    }

    public function byServiceProvider()
    {
        Auth::user()->authorizeRoles( ['Administrator', 'AdminSp' , 'VLA']);
        $service_providers_obj  = new ServiceProvider();
        $service_providers      = $service_providers_obj->getAllServiceProviders();

        return view("booking.by_service_provider", compact( 'service_providers' ));
    }

    public function show( $bk_id )
    {
        Auth::user()->authorizeRoles( ['Administrator', 'AdminSp' , 'VLA']);    
        $service_providers_obj  = new ServiceProvider();
        $service_providers      = $service_providers_obj->getAllServiceProviders();

        return view( "booking.show", compact( 'service_providers' ) );
    }
    
    public function create()
    {
        Auth::user()->authorizeRoles( ['Administrator', 'AdminSp' , 'VLA']);
        $service_providers_obj  = new ServiceProvider();
        $service_providers      = $service_providers_obj->getAllServiceProviders();

        return view("booking.create", compact( 'service_providers' ) );
    }

    public function destroy( $bo_id )
    {
        Auth::user()->authorizeRoles( ['Administrator', 'AdminSp' , 'VLA']);
        $booking_obj = new Booking();
        $response = $booking_obj->deleteBooking( $bo_id );

        return redirect('/booking')->with($response['success'], $response['message']);        
    }

    public function getServiceDatesByDate( $year, $month, $sv_id )
    {
        $init_year = $finish_year = $year;
        $finish_month = $month + 1;
        $init_date   = $init_year . "-" . $month . "-01";
        
        if( $month > 11 )
        {
            $finish_year += 1;
            $finish_month = "01";
        }
        $finish_date = $finish_year . "-" . $finish_month . "-01";

        $booking_obj = new Booking(); 
        return $booking_obj->getBookableServiesByDayWithTime( $sv_id, $init_date, $finish_date);
    }

    public function store()
    {        
        $booking_obj = new Booking();
        $request_type = request('request_type');
        if( $request_type == 0 ) //Direct booking - Booking Bug integration
        {         
            // Validate files
            $validator = Validator::make(request()->all(),
                [
                    'files' => 'nullable|mimes:pdf,png,jpeg,bmp,jpg,doc,docx,xls,xlsx,msg|max:4096',
                    'attachments.*.files' => 'nullable|mimes:pdf,png,jpeg,bmp,jpg,doc,docx,xls,xlsx,msg|max:4096'
                ]
            );
            if($validator->fails())
            {
                return redirect('/booking')->with('error', 'Failed to upload file(s), Check format or size. Formats accepted: pdf,png,jpeg,bmp,jpg,doc,docx,xls,xlsx,msg. and max size: 4MB');
            }

            $service_name = request('ServiceName');
            $client_details = request('client');
            $client_details['ClientEmail']  = ( 
            									!isset( $client_details['ClientEmail'] ) || is_null( $client_details['ClientEmail'] ) ? 
            										'' : 
            										$client_details['ClientEmail'] 
            								  );
            $client_details['Mobile']       = ( 
            									!isset( $client_details['Mobile'] ) || is_null( $client_details['Mobile'] ) ? 
            										'' :
            										str_replace(" ", "", $client_details['Mobile']) 
            								  );
            $service_time = explode( 'T', request('serviceTime') );
            $serviceId = explode( '-', request('ServiceId') );
            
            $service_booking_id = $serviceId[0];

            if( !is_null( request('Language') ) || ( !is_null( request('IsComplex') ) && request('IsComplex') == 1 ) )
            {
                $service_booking_id = $serviceId[1];
            }

            $booking = [
                            'Date' => $service_time[0],
                            'Time' => $service_time[1],
                            'ServiceId' => $service_booking_id,
                        ];
                        
            $booking['ClientBooking'] = [
                                            'Description' => (is_null( request('Desc') ) ? '' : request('Desc') ),
                                            'Language'  => (is_null( request('Language') ) ? '' : request('Language') ),
                                            //Modified without inform us so added just to make it work but emails are generated by Language not IntLanguage
                                            'IntLanguage'  => (is_null( request('Language') ) ? '' : request('Language') ), 
                                            'Safe'      => (is_null( request('Safe') ) ? 'true' : request('Safe') ),
                                            'CIRNumber' => (is_null( request('CIRNumber') ) ? '' : request('CIRNumber') ),
                                            'IsComplex' => (is_null( request('IsComplex') ) ? 0 : request('IsComplex') ),
                                            'IsSafeSMS' => (is_null( request('phonepermission') ) ? 0 : ( request('phonepermission') == 'Yes' ? 1 : 0 ) ),//phonepermission
                                            'IsSafeCall' => (is_null( request('phoneCallPermission') ) ? 0 : ( request('phoneCallPermission') == 'Yes' ? 1 : 0 ) ),//phoneCallPermission
                                            'IsSafeLeaveMessage'  => (is_null( request('phoneMessagePermission') ) ? 0 : ( request('phoneMessagePermission') == 'Yes' ? 1 : 0 ) ),//phoneMessagePermission
                                            'ContactInstructions' => (is_null( request('reContact') ) ? '' : request('reContact') ),//reContact
                                            'RemindNow' => (is_null( request('RemindNow') ) ? 0 : ( request('RemindNow') == 'Yes' ? 1 : 0 ) ),//phonepermission
                                        ];
            

            $sp_id = request('service_provider_id');
            $service_providers_obj   = new ServiceProvider();
            $service_provider_result = $service_providers_obj->getServiceProviderByID( $sp_id );
            $service_provider = json_decode( $service_provider_result['data'] )[0];
            
            $reservation = $booking_obj->createBooking( $client_details, $booking, $service_provider, $service_name );        

            $reservation_details = json_decode( $reservation['reservation'] );

            //Upload attached files
            
            $main_file['files'] = request('files');
            $other_files = request('attachments');

            if( !empty($main_file['files']) && !empty($other_files) )
            {
                $files = array_merge([$main_file], $other_files);
            }
            else if( !empty($main_file['files']) )
            {
                $files[] = $main_file;
            }
            else if( !empty($other_files) )
            {
                $files = $other_files;
            }
            
            if( !empty( $files ) )
            {
                $booking_document = new BookingDocument();
                foreach ($files as $file) 
                {                
                    $fileName = $file['files']->getClientOriginalName();
                    $file['files']->move( public_path('booking_docs') . '/' . $reservation_details->id , $fileName );
                    //Get booking refer from clients name 
                    $clientBokingRefNo = explode(' ',  $reservation_details->client_name );
                    $booking_document->saveBookingDocument( $fileName , $clientBokingRefNo [0] );
                }
            }        

            return redirect('/booking')->with('success', 'Booking saved.');
        } else {
            $response = $booking_obj->requestBooking( request()->all() );
            if( $response )
            {
                return redirect('/booking')->with('success', 'e-Referral sent.');
            }
            else
            {
                return redirect('/booking')->with('error', 'Email not set in service, please contact an administrator.');
            }
        }
    }

    public function list()
    {
        $booking_obj = new Booking(); 
        return $booking_obj->getAllBookingsNextMonthCalendar( date("Y-m-d"), date("Y-m-d", strtotime("+1 month")) );
    }

    public function listCalendar()
    {
        $booking_obj = new Booking(); 
        return $booking_obj->getAllBookingsPerMonthCalendar( request('start'), request('end') ) ;
    }

    public function listCalendarBySp()
    {
        $booking_obj = new Booking(); 
        return $booking_obj->getAllBookingsPerMonthCalendar( request('start'), request('end') , request('sp_id') ) ;
    }

    public function listCalendarByUser()
    {
        $booking_obj = new Booking(); 
        $bookings = $booking_obj->getBookingsByUser() ;
        return array( 'data' => $bookings );
    }

    public function calendar()
    {
        return view("booking.calendar");
    }

    public function updateBooking()
    {
        $booking_obj = new Booking();
        $result = $booking_obj->updateBooking( request()->all() ) ;

        return $result;
    }

    public function updateBookingDetails()
    {
        $booking = request('booking');

        $booking_obj = new Booking(); 
        $result = $booking_obj->updateBookingDetails( json_decode($booking) ) ;

        return $result;
    }

    public function sendSmsReminder()
    {
        $reminder = request('reminder');
        $booking = json_decode( json_encode( request('booking') ), FALSE );
        $sent_sms_obj = new SentSms();
        $result = $sent_sms_obj->sendReminder( $booking );
        return $result;
    }

    public function listLegalHelpBookings()
    {
        $booking_obj = new Booking();
        return array( 'data' => $booking_obj->legalHelpBookings() );
    }

    public function legalHelp()
    {
        return view("booking.legal_help");
    }
}
