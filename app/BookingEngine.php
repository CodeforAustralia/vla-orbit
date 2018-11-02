<?php
namespace App;

use Illuminate\Support\Facades\Mail;
use App\Log;
use App\Service;
use App\User;
use Auth;

/**
 * Booking model for the booking engine functionalities
 * @author Christian Arevalo
 * @version 1.1.1
 * @see  BookingEngineClient
 */

Class BookingEngine extends BookingEngineClient
{
    /**
     * get service bookings
     *
     * @param String $args One or many service ids concatenated by , if is more than one
     * @return array
     */
    public function getServiceBookings( $args )
    {
        $availability = [];
        if($args['service_id'] != '') {
            $url = "/api/auth/service/" . $args['service_id'] . "/booking/" . $args['start_date'] . "/" . $args['end_date'];
            $tokens = $this->getTokens();
            $availability = $this->client->get($tokens, $url);
        }
        return $availability;
    }

    public function getServiceBookingsBySP( $args )
    {
        $service_obj = new Service();
        $services =  $service_obj->getAllServicesBySP($args['sp_id']);
        $service_ids = [];
        $services_info = [];
        foreach ($services as $service) {
            if($service['BookingServiceId'] != ''){
                $service_ids[] = $service['BookingServiceId'];
                $services_info[] = $service;
            }
        }
        $args['service_id'] = implode(',',$service_ids);

        return ['bookings' => self::getServiceBookings($args), 'services' => $services_info];
    }
    /**
     * Get service availability in Booking Engine
     *
     * @param array $args
     * @return void
     */
    public function getServiceAvailability( $args )
    {
        $url = "/api/auth/service/" . $args['sv_id'] . "/availability/" . $args['start_date'] . "/" . $args['end_date'];
        $tokens = $this->getTokens();
        $availability = $this->client->get($tokens, $url);
        return $availability;
    }

     /**
     * Store booking;
     *
     * @param array $booking
     * @return void
     */
    public function storeBooking( $booking )
    {
        $url = "/api/auth/booking";
        $tokens = $this->getTokens();
        $booking_id = $this->client->post($booking,$tokens, $url);
        $log = new Log();
        $log::record( 'CREATE', 'booking', $booking_id, $booking );
        return $booking_id;
    }

     /**
     * Update booking;
     *
     * @param array $booking
     * @return void
     */
    public function updateBooking( $args )
    {
        $url = "/api/auth/booking/" . $args['booking_id']  ;
        $tokens = $this->getTokens();
        $booking = $this->client->patch($args,$tokens, $url);
        $log = new Log();
        $log::record( 'UPDATE', 'booking', $booking->id, $booking );
        return $booking;
    }
    /**
     * Get login token
     *
     * @return void
     */
    private function getTokens()
    {
        $tokens = [];
        if(session('be_tokens') && is_array(session('be_tokens'))) {
            $tokens = session('be_tokens');
        } else {
            $tokens = $this->client->login();
            session(['be_tokens' => $tokens]);
        }
        return $tokens;
    }
}