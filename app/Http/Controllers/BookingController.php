<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Booking;
use App\ServiceProvider;

class BookingController extends Controller
{
    
    public function __construct()
    {       
        $this->middleware('auth');
    }
    
    public function index()
    {       
        $booking_obj = new Booking(); 
        return view("booking.index");
    }

    public function show( $bk_id )
    {
        $service_providers_obj  = new ServiceProvider();
        $service_providers      = $service_providers_obj->getAllServiceProviders();

        return view( "booking.show", compact( 'service_providers' ) );
    }
    
    public function create()
    {
        $service_providers_obj  = new ServiceProvider();
        $service_providers      = $service_providers_obj->getAllServiceProviders();

        return view("booking.create", compact( 'service_providers' ) );
    }

    public function destroy( $bo_id )
    {
        $booking_obj = new Booking();
        $response = $booking_obj->deleteBooking( $bo_id );

        return redirect('/booking')->with($response['success'], $response['message']);        
    }

    public function getServiceDatesByMonth( $month, $sv_id )
    {
        $init_year = $finish_year = date("Y");
        $finish_month = $month + 1;
        $init_date   = $init_year . "-" . $month . "-01";
        
        if( $month > 11 )
        {
            $finish_year += 1;
            $finish_month = "01";
        }
        $finish_date = $finish_year . "-" . $finish_month . "-01";

        $booking_obj = new Booking(); 
        return $booking_obj->getBookableServiesByDayWithTime( $sv_id, date("Y-M"), $finish_date);
    }

    public function store()
    {        
        $client_details = request('client');
        $client_details['ClientEmail']  = ( !isset( $client_details['ClientEmail'] ) || is_null( $client_details['ClientEmail'] ) ? '' : $client_details['ClientEmail'] );
        $client_details['Mobile']       = ( !isset( $client_details['Mobile'] ) || is_null( $client_details['Mobile'] ) ? '' : $client_details['Mobile'] );
        $service_time = explode( 'T', request('serviceTime') );
        $serviceId = explode( '-', request('ServiceId') );
        $booking = [
                        'Date' => $service_time[0],
                        'Time' => $service_time[1],
                        'ServiceId' => (is_null( request('Language') ) ? $serviceId[0] : $serviceId[1] ),
                        'Desc'      => (is_null( request('Desc') ) ? '' : request('Desc') ),
                        'Language'  => (is_null( request('Language') ) ? '' : request('Language') ),
                        'Safe'      => (is_null( request('Safe') ) ? 'true' : request('Safe') ),
                        'CIRNumber' => (is_null( request('CIRNumber') ) ? '' : request('CIRNumber') ),
                    ];
        
        $booking_obj = new Booking(); 
        $reservation = $booking_obj->createBooking( $client_details, $booking );        

        return redirect('/booking')->with('success', 'Booking saved.');
    }

    public function list()
    {
        $booking_obj = new Booking(); 
        return $booking_obj->getAllBookingsPerMonth("2017-07-01", "2017-07-31") ;
    }

    public function listCalendar()
    {
        $booking_obj = new Booking(); 
        return $booking_obj->getAllBookingsPerMonthCalendar( request('start'), request('end') ) ;
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

    public function updateBooking( $booking_ref, $date_time )
    {
        $booking_obj = new Booking(); 
        $result = $booking_obj->updateBooking( $booking_ref, $date_time ) ;

        return $result;
    }
}
