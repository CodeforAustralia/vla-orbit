<?php
namespace App;

use Illuminate\Support\Facades\Mail;
use App\Booking;
use App\Service;
use App\SmsTemplate;
use App\Mail\ReminderSms;

/**
 * Service model for the SMS functionalities
 * @author Christian Arevalo
 * @version 1.0.0
 * @see  OrbitSoap
 */
Class SentSms extends OrbitSoap
{
    /**
     * Get sent SMS by booking
     * @param  int    $ref_id   booking id
     * @return array  $template SMS sent
     */
	public function getSentSMSByBookingRefID ( $ref_id )
	{

        $info = [ 'BookingRef' => $ref_id ];
        $template = json_decode(
                                    $this->client
                                    ->ws_init('GetSentSMSByBookingRefIDasJSON')
                                    ->GetSentSMSByBookingRefIDasJSON( $info )
                                    ->GetSentSMSByBookingRefIDasJSONResult
                                    , true
                                );

        return $template;
	}
    /**
     * Create a sent SMS
     * @param  [type] $template [description]
     * @return [type]           [description]
     */
	public function saveSmSSent( $template )
	{

		$info = [ 'ObjectInstance' => $template ];

        try {
            $response = $this
                        ->client
                        ->ws_init('SaveSmSSent')
                        ->SaveSmSSent( $info );
            if ( $response->SaveSmSSentResult ) {
            	return [ 'success' => 'success' , 'message' => 'Sms reminder was sent.', 'data' => $response->SaveSmSSentResult ];
            } else {
            	return [ 'success' => 'error' , 'message' => 'Ups, something went wrong.' ];
            }
        } catch (\Exception $e) {
            return [ 'success' => 'error' , 'message' =>  $e->getMessage(), 'data' => $template ];
        }
	}
    /**
     * Get next day bookings
     * @return array bookings
     */
    public function getBookings()
    {
        date_default_timezone_set('Australia/Melbourne');
        $today = date('Y-m-d h:i:s');

        //increment 7 days
        $tomorrow      = date( 'Y-m-d', strtotime($today.'+ 1 day')  ) ;


        $booking_obj            = new Booking();
        $tomorrow_bookings      = $booking_obj->getAllBookingsByDay( $tomorrow );


        return ['tomorrow_bookings' => $tomorrow_bookings ];
    }
    /**
     * Send SMS from the next day bookings
     *
     */
    public function sendMessages()
    {
        $bookings = self::getBookings();

        //Tomorrow
        $tomorrow_bookings = $bookings['tomorrow_bookings'];
        if ( isset( $tomorrow_bookings->Bookings ) ) {
            if ( sizeof( $tomorrow_bookings->Bookings ) == 1 ) {
                $tomorrow_bookings->Bookings = [$tomorrow_bookings->Bookings];
            }
            foreach ( $tomorrow_bookings->Bookings as $booking ) {

                if ( isset( $booking->Mobile ) && $booking->Mobile != '' ) {
                    self::sendReminder( $booking );
                }
            }
        }
        /*
        //In a week
        $week_from_now_bookings = $bookings['week_from_now_bookings'];
        if( isset( $week_from_now_bookings->Bookings ) )
        {
            if( sizeof( $week_from_now_bookings->Bookings ) == 1 )
            {
                $week_from_now_bookings->Bookings = [$week_from_now_bookings->Bookings];
            }
            foreach ( $week_from_now_bookings->Bookings as $key=>$weekly_booking )
            {
                if( isset( $weekly_booking->Mobile ) && $weekly_booking->Mobile != '' )
                {
                    self::sendReminder( $weekly_booking );
                }
            }
        }*/
    }

    /**
     * Send SMS
     * @param  Object  $booking booking
     */
    public function sendReminder( $booking )
    {
        $args = [];

        //get client info
        $args['client_name']  = $booking->FirstName;
        $args['client_phone'] = $booking->Mobile;
        $args['date'] = $booking->BookingDate;
        $args['time'] = $booking->BookingTime;

        //get service info

        $service_obj = new Service();
        $services = $service_obj->getAllServices();

        $current_service = [];
        foreach ( $services as $service ) {
            if ( $service['BookingServiceId'] == $booking->ServiceId
                || $service['BookingInterpritterServiceId'] == $booking->ServiceId  ) {

                $current_service = $service;
            }
        }

        $args['service_name']     = $current_service['ServiceName'];
        $args['service_location'] = htmlspecialchars_decode($current_service['Location'], ENT_QUOTES);
        $args['service_phone']    = $current_service['Phone'];

        if(!isset($booking->template) || $booking->template == '') {
            //SMS template
            $sms_template_obj = new SmsTemplate();
            $sms_template = $sms_template_obj->getTemplateByServiceId( $current_service['ServiceId'] );
            $args['template'] = $sms_template['Template'];

            //replace tags in template
            $template = $sms_template_obj->replaceTemplateTags($args);
        } else {
            $sms_template['TemplateId'] = 1;
            $template = $booking->template;
        }

        //send sms
        $client_phone = preg_replace("/\D/", "", $args['client_phone']) ;

        if ( is_numeric($client_phone) && $booking->IsSafeSMS ) {
            Mail::to( $args['client_phone'] . '@e2s.pcsms.com.au'  )->send( new ReminderSms( $template ) );
            //store log information
            $sent_sms_info = array(
                                    'BookingRef' => $booking->RefNo,
                                    'SentDate'   => date('Y-m-d') . 'T' . date('H:i:s'),
                                    'TemplateId' => $sms_template['TemplateId']
                                );
            $response = self::saveSmSSent( $sent_sms_info );
        }
    }
}