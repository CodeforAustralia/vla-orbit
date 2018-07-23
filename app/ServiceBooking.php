<?php
namespace App;

use Auth;

/**
 * Service Booking model for the service booking functionalities
 * @author Sebastian Currea
 * @version 1.2.0
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



}