<?php
namespace App;

use App\Log;
use App\Service;
use App\ServiceProvider;
use App\User;
use Auth;

/**
 * Booking model for the booking engine functionalities
 * @author Christian Arevalo and Sebastian Currea
 * @version 1.1.2
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

    /**
     * Get all bookings by service provider
     *
     * @param array $args   Array containign a Service provider id
     * @return array    Information of bookings, service names, avaiability and booking status object
     */
    public function getServiceBookingsBySP( $args )
    {
        $service_obj = new Service();
        $services =  $service_obj->getAllServicesBySP($args['sp_id']);
        $service_ids = [];
        $services_info = [];
        $services_availability = [];
        foreach ($services as $service) {
            if($service['BookingServiceId'] != ''){
                $service_ids[] = $service['BookingServiceId'];
                $services_info[] = $service;
                $services_availability[$service['BookingServiceId']] = self::getServiceAvailability([
                                                                                                        'sv_id' => $service['BookingServiceId'],
                                                                                                        'start_date' => $args['start_date'],
                                                                                                        'end_date' => $args['end_date']
                                                                                                    ]);
            }
        }
        $args['service_id'] = implode(',',$service_ids);

        return [
                    'bookings'       => self::getServiceBookings($args),
                    'services'       => $services_info,
                    'services_availability' => $services_availability,
                    'booking_status' => self::getAllBookingStatus()
                ];
    }
    /**
     * Get service availability in Booking Engine
     *
     * @param array $args  Array of service id, start and end date
     * @return array    Array of service availability
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
     * @return int  booking ID
     */
    public function storeBooking( $booking )
    {
        $url = "/api/auth/booking";
        $tokens = $this->getTokens();

        $user = Auth::user();
        $booking['created_by'] = $user->id;

        $booking_id = $this->client->post($booking,$tokens, $url);

        $log = new Log();
        $log::record( 'CREATE', 'booking', $booking_id, $booking );

        return $booking_id;
    }

    /**
     * Store Service
     *
     * @param array $params Service paramenters including service provider name
     * @return int  Id of saved service
     */
    public function storeService( $params )
    {
        $url = "/api/auth/service";
        $tokens = $this->getTokens();

        $user = Auth::user();
        $service_id = $this->client->post($params,$tokens, $url);

        if($service_id) {
            //Create service booking one
            $log = new Log();
            $log::record( 'CREATE', 'booking_engine_service', $service_id, $params );
            return $service_id;
        } else {
            return false;
        }

    }

    /**
     * Update booking;
     *
     * @param array $booking
     * @return array Bookin ginformation
     */
    public function updateBooking( $args )
    {
        $url = "/api/auth/booking/" . $args['booking_id'] ;
        $tokens = $this->getTokens();

        $user = Auth::user();
        $args['updated_by'] = $user->id;

        $booking = $this->client->patch($args,$tokens, $url);
        $log = new Log();
        $log::record( 'UPDATE', 'booking', $booking->id, $booking );
        return $booking;
    }


    /**
     * Get all booking status
     *
     * @return array Array of booking status
     */
    public function getAllBookingStatus()
    {
        $url = "/api/auth/booking_status";
        $tokens = $this->getTokens();
        $booking_status = $this->client->get($tokens,$url);
        return $booking_status;
    }

    /**
     * Delete Booking
     *
     * @param int $booking_id
     * @return boolean
     */
    public function deleteBooking($booking_id)
    {
        $url = "/api/auth/booking/" . $booking_id;
        $tokens = $this->getTokens();
        $response = $this->client->delete($tokens, $url);
        return $response;
    }
    /**
     * Get booking Services
     *
     * @return array Array of services
     */
    public function getServices()
    {
        $url = "api/auth/service";
        $tokens = $this->getTokens();
        $services = $this->client->get($tokens, $url);
        return json_decode(json_encode($services), true);
    }

    /**
     * Get service by service provider name
     *
     * @param string $service_provider_name Service provider name
     * @return array Array of services
     */
    public function getServicesBySPName($service_provider_name)
    {
        $url =  "api/auth/service/service_provider/" . $service_provider_name;
        $tokens = $this->getTokens();
        $services = $this->client->get($tokens, $url);
        return json_decode(json_encode($services), true);

    }
    /**
     * Get All Booking by Day
     *
     * @param date $date YYY-MM-DD
     * @return array    Array of bookings in a given date
     */
    public function getAllBookingsByDay($date)
    {
        $url = "api/auth/booking/date/".$date;
        $tokens = $this->getTokens();
        $bookings = $this->client->get($tokens, $url);
        return $bookings;
    }

    /**
     * Get future booking by office name
     *
     * @return Array Bookings for the given office
     */
    public function getFutureBookingsByUserSPName()
    {
        $bookings = [];
        $service_providers_obj  = new ServiceProvider();
        $user = Auth::user();
        if($user->sp_id > 0) {
            $service_provider = $service_providers_obj->getServiceProviderByID($user->sp_id);
            $service_provider_info = json_decode($service_provider['data'])[0];
            $sp_name = $service_provider_info->ServiceProviderName;
            $today = date("Y-m-d");
            $three_moths_time = strtotime("+3 Months");
            $three_moths = date("Y-m-d", $three_moths_time);

            $url = 'api/auth/booking/' . $sp_name . '/'. $today . '/' . $three_moths;
            $tokens = $this->getTokens();
            $bookings = $this->client->get($tokens, $url);
        }
        return $bookings;
    }

    /**
     * Get bookings by current user using the app
     *
     * @return Array Bookings for the current user
     */
    public function getBookingsByUser()
    {
        $user = Auth::user();
        $url = 'api/auth/booking/orbit_user/' . $user->id;
        $tokens = $this->getTokens();
        $bookings = $this->client->get($tokens, $url);

        return $bookings;
    }
    /**
     * Return the bookings made by LH users
     *
     * @return Array    Legal help bookings
     */
    public function legalHelpBookings()
    {
        $bookings = [];
        $legal_help_bookings = [];
        $start_date = date("Y-m-d", strtotime("2017-08-01"));
        $end_date = date("Y-m-d", strtotime("+3 Months"));
        $url = 'api/auth/booking/'. $start_date . '/' . $end_date;
        $tokens = $this->getTokens();
        $bookings = $this->client->get($tokens, $url);
        $bookings_obj = ['bookings' => $bookings ];
        // Filter bookings
        $legal_help_bookings = $this->filterLegalHelpBookings($bookings_obj);
        return $legal_help_bookings;

    }
    /**
     * Filter Legal Help Bookings
     *
     * @param Array $bookings All bookings
     * @return Array    Array of bookings made by legal help
     */
    private function filterLegalHelpBookings($bookings)
    {
        $user = new User();
        $legal_help_id = 112;
        $legal_help_bookings=[];
        foreach ($bookings['bookings'] as $booking) {

            $user_id = $booking->created_by;
            $user_info = $user->find($user_id);
            if ( $user_info && $user_info->sp_id == $legal_help_id ) {
                $booking->created_by = $user_info->name;
                $legal_help_bookings[] = $booking;
            }
        }
        return $legal_help_bookings;
    }

    /**
     * Get statistics for one day
     *
     * @return Array    Statistics for a given day
     */
    public function getStatsDay($date)
    {
        $url = 'api/auth/stats_day/' . $date;
        $tokens = $this->getTokens();
        $bookings = $this->client->get($tokens, $url);

        return $bookings;
    }

    /**
     * Get statistics for one day
     *
     * @return Array    Statistics for a given period of time
     */
    public function getStatsPeriod($date)
    {
        $url = 'api/auth/stats_period/' . $date;
        $tokens = $this->getTokens();
        $bookings = $this->client->get($tokens, $url);

        return $bookings;
    }

    /**
     * Get login token
     *
     * @return String   Token
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