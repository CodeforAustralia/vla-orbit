<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ServiceBooking;
use App\Service;
use App\BookingEngine;
use Auth;

/**
 * Service Booking  Controller.
 * Controller for the service booking functionalities
 * @author Christian Arevalo and Sebastian Currea
 * @version 1.2.0
 * @see  Controller
 */
class ServiceBookingController extends Controller
{
    /**
     * Question contructor. Create a new instance
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of service booking
     * @return view service booking information
     */
    public function index()
    {
        Auth::user()->authorizeRoles( ['Administrator','AdminSp'] );
        return view("service_booking.index");
    }

    /**
     * List all service booking
     * @return array list of all service booking
     */
    public function list()
    {
        Auth::user()->authorizeRoles( ['Administrator','AdminSp'] );
        $service_booking_obj = new ServiceBooking();
        $service_obj = new Service();
        $booking_engine_obj = new BookingEngine();

        // orbit services
        $services = $service_obj->getAllServices();
        //booking engine services
        $booking_services = $booking_engine_obj->getServices();
        // service bookings
        $service_bookings = $service_booking_obj->getAllServiceBookings();

        foreach ($service_bookings as $key_sb => $service_booking) {
            $service_bookings[$key_sb]['ServiceName'] = '';
            $service_bookings[$key_sb]['ServiceProviderName'] = '';
            $service_bookings[$key_sb]['BookingServiceName'] = '';
            foreach ($services as $key => $service) {
                if($service_booking['ServiceId'] == $service['ServiceId']) {
                    $service_bookings[$key_sb]['ServiceName'] = $service['ServiceName'];
                    $service_bookings[$key_sb]['ServiceProviderName'] = $service['ServiceProviderName'];
                    break;
                }
            }
            foreach ($booking_services as $key => $booking_service) {
                if($service_booking['BookingServiceId'] == $booking_service['id']) {
                    $service_bookings[$key_sb]['BookingServiceName'] = $booking_service['name'];
                    break;
                }
            }
        }


        return ['data' => $service_bookings];
    }

    /**
     * Show the form for creating a nee service booking
     * @return view service booking creation page
     */
    public function create()
    {
        Auth::user()->authorizeRoles( ['Administrator','AdminSp'] );
        $service_obj = new Service();
        $booking_engine_obj = new BookingEngine();
        $services = $service_obj->getAllServices();
        $booking_services = $booking_engine_obj->getServices();
		usort($services, function($a, $b){ return strcmp($a["ServiceName"], $b["ServiceName"]); });
    	return view("service_booking.create", compact('services', 'booking_services'));
    }

    /**
     * Store a newly or updated service booking in the data base
     * @return mixed service booking listing page with success/error message
     */
    public function store()
    {
        Auth::user()->authorizeRoles( ['Administrator','AdminSp'] );
    	//Validations
    	$this->validate(request(), [
    		'ServiceId'=>'required',
            'BookingServiceId' => 'required|numeric',
            'InternalBookingServId' => 'numeric|nullable',
            'ResourceId' => 'required|numeric',
            'ServiceLength'=> 'required|numeric',
            'IntServiceLength' => 'numeric|nullable',
    	]);
    	$service_booking = new ServiceBooking();
    	$response = $service_booking->saveServiceBooking( request()->all() );
    	return redirect('/service_booking')->with( $response['success'], $response['message'] );
    }

    /**
     * Display a specific service booking
     * @param  integer $sb_id service booking id
     * @return view single service booking information page
     */
    public function show( $sb_id )
    {
        Auth::user()->authorizeRoles( ['Administrator','AdminSp'] );

        $service_booking = new ServiceBooking();
        $result = $service_booking->getServiceBookingByID( $sb_id );

        $service_obj = new Service();
        $services = $service_obj->getAllServices();

        $booking_engine_obj = new BookingEngine();
        $booking_services = $booking_engine_obj->getServices();

        usort($services, function($a, $b){ return strcmp($a["ServiceName"], $b["ServiceName"]); });
        if ( isset($result['data']) ) {
            $current_service_booking = json_decode( $result['data'] )[0];
            return view( "service_booking.show", compact( 'current_service_booking','services' ,'booking_services') );
        } else {
            return redirect('/service_booking')->with( $response['success'], $response['message'] );
        }

    }

    /**
     * Remove the specified service booking from data base.
     * @param  integer $sb_id service booking id
     * @return mixed question listing page with success/error message
     */
    public function destroy($sb_id)
    {
        Auth::user()->authorizeRoles( ['Administrator','AdminSp'] );
        $serviceBooking = new ServiceBooking();
        $response = $serviceBooking->deleteServiceBookingById($sb_id);

        return redirect('/service_booking')->with( $response['success'], $response['message'] );
    }

}