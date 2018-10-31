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
 * @version 1.0.1
 * @see  BookingEngineClient
 */

Class BookingEngine extends BookingEngineClient
{
    public function getServiceBookings( $args )
    {
        $url = "/api/auth/service/" . $args['service_id'] . "/booking/" . $args['start_date'] . "/" . $args['end_date'];

        $tokens = $this->getTokens();

        $availability = $this->client->get($tokens, $url);
        return $availability;
    }

    public function getServiceBookingsBySP( $args )
    {
        $service_obj = new Service();
        $services =  $service_obj->getAllServicesBySP($args['sp_id']);
        $service_ids = implode(',',array_diff(array_column($services,'BookingServiceId'), [''])); //Check for services with Booking Ids and exclude services without it

        $url = "/api/auth/service/" . $service_ids. "/booking/" . $args['start_date'] . "/" . $args['end_date'];

        $tokens = $this->getTokens();

        $availability = $this->client->get($tokens, $url);
        return $availability;
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

    public function storeBooking($booking)
    {
        $url = "/api/auth/booking";
        $tokens = $this->getTokens();
        $booking_id = $this->client->post($booking,$tokens, $url);
        $log = new Log();
        $log::record( 'CREATE', 'booking', $booking_id, $booking );
        return $booking_id;
    }

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