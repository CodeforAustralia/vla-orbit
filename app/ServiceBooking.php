<?php
namespace App;

use Auth;
use App\Service;
use App\BookingEngine;

/**
 * Service Booking model for the service booking functionalities
 * @author Sebastian Currea & Christian Arevalo
 * @version 1.2.1
 * @see  OrbitSoap
 */
Class ServiceBooking extends OrbitSoap
{
    /**
     * Get all service booking including service id, resource, booking bug service id and times
     * @return Object $services list of all service bookings
     */
    public function getAllServiceBookings()
    {
        $services = json_decode(    $this
                                    ->client
                                    ->ws_init('GetAllServiceBookingsasJSON')
                                    ->GetAllServiceBookingsasJSON()
                                    ->GetAllServiceBookingsasJSONResult
                                    , true
                                );
        return $services;
    }

    /**
     * Get all services with booking including all service information
     * @return Object list of all service with bookings
     */
    public function getAllServicesWithBookings()
    {
        $services = $this
                    ->client
                    ->GetAllServiceBookingsWithReferrals()
                    ->GetAllServiceBookingsWithReferralsResult
                    ->OrbitService;

        return $services;
    }

    /**
     * Create or update a service booking
     * @param  array $sb_params service booking details
     * @return array            success or error message
     */
    public function saveServiceBooking( $sb_params )
    {
        $info = [ 'ObjectInstance' => $sb_params ];

        try {
            $response = $this
                        ->client
                        ->ws_init( 'SaveServiceBooking' )
                        ->SaveServiceBooking( $info );
            // Redirect to index
            if( $response->SaveServiceBookingResult > 0 ) {
                return [ 'success' => 'success' , 'message' => 'Service Booking saved.'];
            } else {
                return [ 'success' => 'error' , 'message' => 'Ups, something went wrong.' ];
            }
        } catch ( \Exception $e ) {
            return [ 'success' => 'error' , 'message' =>  $e->getMessage() ];
        }
    }
    /**
     * Delete a service booking by id
     * @param  int   $sb_id  service booking id
     * @return array         success or error message
     */
    public function deleteServiceBookingById($sb_id)
    {
        // Create call request
        $info = [ 'RefNumber' => $sb_id ];

        try {
            $response = $this
                        ->client
                        ->ws_init('DeleteServiceBooking')
                        ->DeleteServiceBooking( $info );
            if ( $response->DeleteServiceBookingResult ) {
                return [ 'success' => 'success' , 'message' => 'Service Booking deleted.' ];
            } else {
                return [ 'success' => 'error' , 'message' => 'Ups, something went wrong.' ];
            }
        } catch ( \Exception $e ) {
            return [ 'success' => 'error' , 'message' =>  $e->getMessage() ];
        }

    }
    /**
     * Get service booking by id
     * @param  int   $sb_id service booking id
     * @return array        success or error message
     */
    public function getServiceBookingByID($sb_id)
    {
        // Create call request
        $info = [ 'ReNumber' => $sb_id ];

        try {
            $response =     $this
                            ->client
                            ->ws_init('GetServiceBookingsByIdasJSON')
                            ->GetServiceBookingsByIdasJSON( $info );

            if ( $response->GetServiceBookingsByIdasJSONResult ) {
                return [ 'success' => 'success' , 'message' => 'Service Booking.', 'data' => $response->GetServiceBookingsByIdasJSONResult ];
            } else {
                return [ 'success' => 'error' , 'message' => 'Ups, something went wrong.' ];
            }
        } catch (\Exception $e) {
            return [ 'success' => 'error' , 'message' =>  $e->getMessage() ];
        }
    }

    /**
     * Get servive booking information by service ID
     *
     * @param int $sv_id    Service ID
     * @return array        Booking information
     */
    public function getServiceBookingByServiceId($sv_id)
    {
        $service_bookings = self::getAllServiceBookings();
        foreach ($service_bookings as $service_booking) {
            if($service_booking['ServiceId'] == $sv_id) {
                return $service_booking;
            }
        }
        return [];
    }

    /**
     * Get service information in format to be sent to Booking Engine
     *
     * @param int $sv_id Service ID
     * @return array
     */
    public function getServiceDataWithFormat($sv_id)
    {
        $data = self::getServiceBookingByServiceId($sv_id);
        if(empty($data)){ //Validate that this do not exist
            $service_obj = new Service();
            $service = $service_obj->getServiceByID($sv_id);

            if (isset( $service['data'] ) ) {
                $current_service = json_decode( $service['data'] )[0];
                $be_request = [
                    'name'  => $current_service->ServiceName,
                    'phone' => $current_service->Phone,
                    'email' => $current_service->Email,
                    'description' => $current_service->Description,
                    'sp_name' => $current_service->ServiceProviderName
                ];

                return $be_request;
            }
        }
        return [];
    }

    /**
     * Activate serice in Booking Engine saving the returned ID in the booking service table
     *
     * @param int $sv_id Service ID
     * @return array
     */
    public function activateService($sv_id)
    {
        $service = self::getServiceDataWithFormat($sv_id);
        //Create Service in BE
        $booking_engine_obj = new BookingEngine();
        $be_sv_id = $booking_engine_obj->storeService( $service );
        if($be_sv_id) {
                //Store ID of service from BE in Orbit
                //Parameters
                $sb_params = [
                                'ServiceId'=> $sv_id, //Orbit one
                                'BookingServiceId' => $be_sv_id, //Booking Engine service ID
                                'InternalBookingServId' => 0, //Deprecated field
                                'ResourceId' => 0, //Deprecated field
                                'ServiceLength'=> 0, //Deprecated field
                                'IntServiceLength' => 0, //Deprecated field
                            ];
                $output = self::saveServiceBooking( $sb_params );
                //return confirmation
                return $output;

        } else {
            return response()->json(['error'=>'Error '],400);
        }
    }
}