<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ServiceBooking;
use App\Service;
use App\ServiceProvider;
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
        Auth::user()->authorizeRoles(['Administrator','AdminSp']);
        return view("service_booking.index");
    }

    /**
     * List all service booking
     * @return array list of all service booking
     */
    public function list()
    {
        Auth::user()->authorizeRoles(['Administrator','AdminSp']);
        $user = Auth::user();
        $service_booking_obj = new ServiceBooking();
        $service_obj = new Service();
        $booking_engine_obj = new BookingEngine();
        //booking engine services
        $booking_services = $booking_engine_obj->getServices();
        // service bookings
        $service_bookings = $service_booking_obj->getAllServiceBookings();

        if (\App\Http\helpers::getRole() !== 'Administrator') {
            $services = $service_obj->getAllServicesBySP($user->sp_id);
            $service_bookings =  $this->filterServiceBooking($service_bookings, $services);
        } else {
            $services = $service_obj->getAllServices();
        }
        // orbit services

        foreach ($service_bookings as $key_sb => $service_booking) {
            $service_bookings[$key_sb]['ServiceName'] = '';
            $service_bookings[$key_sb]['ServiceProviderName'] = '';
            $service_bookings[$key_sb]['BookingServiceName'] = '';
            foreach ($services as $key => $service) {
                if ($service_booking['ServiceId'] == $service['ServiceId']) {
                    $service_bookings[$key_sb]['ServiceName'] = $service['ServiceName'];
                    $service_bookings[$key_sb]['ServiceProviderName'] = $service['ServiceProviderName'];
                    break;
                }
            }
            foreach ($booking_services as $key => $booking_service) {
                if ($service_booking['BookingServiceId'] == $booking_service['id']) {
                    $service_bookings[$key_sb]['BookingServiceName'] = $booking_service['name'];
                    break;
                }
            }
        }


        return ['data' => $service_bookings];
    }
    /**
     * Get service bookings list with the services that belong to the service provider of the user
     *
     * @param array $service_bookings
     * @param array $services
     * @return void
     */
    private function filterServiceBooking($service_bookings, $services)
    {
        $user = Auth::user();
        $service_bookings_list = [];
        foreach ($services as $service) {
            foreach ($service_bookings as $service_booking) {
                if ($service_booking['ServiceId'] == $service['ServiceId']) {
                    $service_bookings_list [] = $service_booking;
                    break;
                }
            }
        }
        return $service_bookings_list;
    }

    /**
     * Show the form for creating a nee service booking
     * @return view service booking creation page
     */
    public function create()
    {
        Auth::user()->authorizeRoles(['Administrator','AdminSp']);
        $user = Auth::user();
        $service_booking = new ServiceBooking();
        $booking_engine_obj = new BookingEngine();
        $service_obj = new Service();
        if (\App\Http\helpers::getRole() !== 'Administrator') {
            $service_providers_obj = new ServiceProvider();
            $service_provider_result = $service_providers_obj->getServiceProviderByID($user->sp_id);
            $service_provider = json_decode($service_provider_result['data'])[0];
            $services = $service_obj->getAllServicesBySP($user->sp_id);
            $booking_services = $booking_engine_obj->getServicesBySPName($service_provider->ServiceProviderName);
        } else {
            $services = $service_obj->getAllServices();
            $booking_services = $booking_engine_obj->getServices();
        }
        usort($services, function ($a, $b) {
            return strcmp($a["ServiceName"], $b["ServiceName"]);
        });
        return view("service_booking.create", compact('services', 'booking_services'));
    }

    /**
     * Store a newly or updated service booking in the data base
     * @return mixed service booking listing page with success/error message
     */
    public function store()
    {
        Auth::user()->authorizeRoles(['Administrator','AdminSp']);
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
        $response = $service_booking->saveServiceBooking(request()->all());
        return redirect('/service_booking')->with($response['success'], $response['message']);
    }

    /**
     * Display a specific service booking
     * @param  integer $sb_id service booking id
     * @return view single service booking information page
     */
    public function show($sb_id)
    {
        Auth::user()->authorizeRoles(['Administrator','AdminSp']);
        $user = Auth::user();
        $service_booking = new ServiceBooking();
        $result = $service_booking->getServiceBookingByID($sb_id);
        $booking_engine_obj = new BookingEngine();
        $service_obj = new Service();
        if (\App\Http\helpers::getRole() !== 'Administrator') {
            $service_providers_obj = new ServiceProvider();
            $service_provider_result = $service_providers_obj->getServiceProviderByID($user->sp_id);
            $service_provider = json_decode($service_provider_result['data'])[0];
            $services = $service_obj->getAllServicesBySP($user->sp_id);
            $booking_services = $booking_engine_obj->getServicesBySPName($service_provider->ServiceProviderName);
        } else {
            $services = $service_obj->getAllServices();
            $booking_services = $booking_engine_obj->getServices();
        }
        usort($services, function ($a, $b) {
            return strcmp($a["ServiceName"], $b["ServiceName"]);
        });
        if (isset($result['data'])) {
            $current_service_booking = json_decode($result['data'])[0];
            return view("service_booking.show", compact('current_service_booking', 'services', 'booking_services'));
        } else {
            return redirect('/service_booking')->with($response['success'], $response['message']);
        }
    }

    /**
     * Remove the specified service booking from data base.
     * @param  integer $sb_id service booking id
     * @return mixed question listing page with success/error message
     */
    public function destroy($sb_id)
    {
        Auth::user()->authorizeRoles(['Administrator','AdminSp']);
        $serviceBooking = new ServiceBooking();
        $response = $serviceBooking->deleteServiceBookingById($sb_id);

        return redirect('/service_booking')->with($response['success'], $response['message']);
    }

    /**
     * Activate service request which would try to create it in Booking engine
     *
     * @param Request $request Service Id
     * @return json success or error message
     */
    public function activateService(Request $request)
    {
        Auth::user()->authorizeRoles(['Administrator','AdminSp','AdminSpClc']);

        $sv_id = $request->id;
        $serviceBooking = new ServiceBooking();
        $response = $serviceBooking->activateService($sv_id);

        return $response;
    }
}
