<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ServiceBooking;
use App\Service;
use Auth;

class ServiceBookingController extends Controller
{
    public function __construct()
    {       
        $this->middleware('auth');
    }   
    /**
     * 
     * @return view index view
     */
    public function index()
    {
        Auth::user()->authorizeRoles( ['Administrator']);
        return view("service_booking.index");    
    }
    /**
     * Display all service bookings
     * @return array list of service bookings
     */
    public function list()
    {
        Auth::user()->authorizeRoles( ['Administrator']);
        $serviceBooking = new ServiceBooking();
        $result  = $serviceBooking->getAllServiceBookings();
        
        return array( 'data' => $result );
    }
    /**
     * Open the view to create a new Service Booking
     * @return View service booking create form
     */
    public function create()
    {
        Auth::user()->authorizeRoles( ['Administrator']);
    	$service_obj = new Service();
        $services = $service_obj->getAllServices(); 
        
		usort($services, function($a, $b){ return strcmp($a["ServiceName"], $b["ServiceName"]); }); 

    	return view("service_booking.create", compact('services'));
    }
    /**
     * Create or update a service booking
     * @return View service bookling view
     */
    public function store()
    {
        Auth::user()->authorizeRoles( ['Administrator']);
    	//Validations
    	$this->validate(request(), [
    		'ServiceId'=>'required',
            'BookingServiceId' => 'required|numeric',
            'InternalBookingServId' => 'numeric|nullable',
            'ResourceId' => 'required|numeric',
            'ServiceLength'=> 'required|numeric',
            'IntServiceLength' => 'numeric|nullable',
    	]);
    	$serviceBooking = new ServiceBooking();
    	$response = $serviceBooking->saveServiceBooking( request()->all() );
    	return redirect('/service_booking')->with( $response['success'], $response['message'] );
    }
    /**
     * Show a selected service booking to edit
     * @param  Integer $sb_id service booking id
     * @return View        confirmation message with success or redirection otherwise
     */
    public function show( $sb_id )
    {
        Auth::user()->authorizeRoles( ['Administrator']);
        $serviceBooking = new ServiceBooking();
        $result = $serviceBooking->getServiceBookingByID( $sb_id );
        $service_obj = new Service();
        $services = $service_obj->getAllServices();         
        usort($services, function($a, $b){ return strcmp($a["ServiceName"], $b["ServiceName"]); }); 
        if(isset($result['data'])) 
        {
            $current_service_booking = json_decode( $result['data'] )[0];                
            return view( "service_booking.show", compact( 'current_service_booking','services' ) );         
        } 
        else 
        {
            return redirect('/service_booking')->with( $response['success'], $response['message'] );
        }  

    }
    /**
     * Delete service booking
     * @param  Integer $sb_id service booking id
     * @return View        redirect to the service booking view with delete message
     */
    public function destroy($sb_id)
    {
        Auth::user()->authorizeRoles( ['Administrator']);
        $serviceBooking = new ServiceBooking();
        $response = $serviceBooking->deleteServiceBookingById($sb_id);
        
        return redirect('/service_booking')->with( $response['success'], $response['message'] );
    }    

}