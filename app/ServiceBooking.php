<?php
namespace App;

use Auth;

Class ServiceBooking
{
	public $client;

	function __construct() 
	{
	       $this->client = (new \App\Repositories\VlaSoap)->ws_init();
	}

	/**
	 * Get all service booking including service id, resource, booking bug service id and times
	 * @return Object list of all service bookings
	 */
	public function getAllServiceBookings()
	{
        $services = json_decode($this->client->GetAllServiceBookingsasJSON()->GetAllServiceBookingsasJSONResult, true);
        return $services;
	}
	/**
	 * Create or update a service booking
	 * @param  array $sb_params parameters of a service booking
	 * @return array            Creation or update status
	 */
	public function saveServiceBooking( $sb_params ) 
    {
        $info = [ 'ObjectInstance' => $sb_params ];

        try 
        {
            $response = $this->client->SaveServiceBooking( $info );            
            // Redirect to index        
            if( $response->SaveServiceBookingResult > 0 )
            {
                return array( 'success' => 'success' , 'message' => 'Service Booking saved.');
            } 
            else 
            {
                return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        }
        catch (\Exception $e) 
        {            
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );       
        }
    }
    /**
     * Delete a service booking by id
     * @param  Integer $sb_id  service booking id
     * @return array        Deletion status
     */
    public function deleteServiceBookingById($sb_id)
    {
        // Create call request        
        $info = [ 'RefNumber' => $sb_id ];

        try 
        {
            $response = $this->client->DeleteServiceBooking( $info );
            if( $response->DeleteServiceBookingResult )
            {
                return array( 'success' => 'success' , 'message' => 'Service Booking deleted.' );
            } else 
            {
                return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        }
        catch (\Exception $e) 
        {            
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );       
        }

    }
    /**
     * Get service booking by id
     * @param  Integer $sb_id service booking id
     * @return array        Getting status
     */
    public function getServiceBookingByID($sb_id)
    {        
        // Create call request        
        $info = [ 'ReNumber' => $sb_id ];

        try 
        {
            $response = $this->client->GetServiceBookingsByIdasJSON( $info );
            
            if( $response->GetServiceBookingsByIdasJSONResult )
            {                
                return array( 'success' => 'success' , 'message' => 'Service Booking.', 'data' => $response->GetServiceBookingsByIdasJSONResult );
            } else 
            {
                return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        }
        catch (\Exception $e) 
        {          
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );       
        }
    }



}