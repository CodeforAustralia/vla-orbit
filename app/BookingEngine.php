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
 * @version 1.0.0
 * @see  BookingEngineClient
 */

Class BookingEngine extends BookingEngineClient
{
    public function getServiceBookings( $args )
    {
        $url = "/api/auth/service/" . $args['service_id'] . "/booking/" . $args['start_date'] . "/" . $args['end_date'];
        $tokens = [];

        if(session('be_tokens')) {
            $tokens = session('be_tokens');
        } else {
            $tokens = $this->client->login();
            session(['be_tokens' => $tokens]);
        }

        $availability = $this->client->get($tokens, $url);
        return $availability;
    }

    public function getServiceBookingsBySP( $args )
    {
        $service_obj = new Service();
        $services =  $service_obj->getAllServicesBySP($args['sp_id']);
        $service_ids = implode(',',array_diff(array_column($services,'BookingServiceId'), [''])); //Check for services with Booking Ids and exclude services without it

        $url = "/api/auth/service/" . $service_ids. "/booking/" . $args['start_date'] . "/" . $args['end_date'];
        $tokens = [];

        if(session('be_tokens')) {
            $tokens = session('be_tokens');
        } else {
            $tokens = $this->client->login();
            session(['be_tokens' => $tokens]);
        }

        $availability = $this->client->get($tokens, $url);
        return $availability;
    }

    public function getServiceAvailability( $args )
    {

    }
}