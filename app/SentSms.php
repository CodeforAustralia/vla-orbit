<?php
namespace App;

use Illuminate\Support\Facades\Mail;
use App\Booking;
use App\Service;
use App\SmsTemplate;
use App\Mail\ReminderSms;

Class SentSms
{

	public function getSentSMSByBookingRefID ( $ref_id )
	{		
		// Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();

        $info = [ 'BookingRef' => $ref_id ];
        $template = json_decode(
                                    $client
                                    ->GetSentSMSByBookingRefIDasJSON( $info )
                                    ->GetSentSMSByBookingRefIDasJSONResult, 
                                    true
                                );

        return $template;
	}

	public function saveSmSSent( $template )
	{
		// Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();

		$info = [ 'ObjectInstance' => $template ];

        try 
        {
            $response = $client->SaveSmSSent( $info );         
            if( $response->SaveSmSSentResult )
            {
            	return array( 'success' => 'success' , 'message' => 'Sms reminder was sent.', 'data' => $response->SaveSmSSentResult );
            } 
            else
            {
            	return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        }
        catch (\Exception $e) 
        {      
            return array( 'success' => 'error' , 'message' =>  $e->getMessage(), 'data' => $template ); 
        }
	}

    public function getBookings()
    {        
        date_default_timezone_set('Australia/Melbourne');
        $today = date('Y-m-d h:i:s');

        //increment 7 days
        $tomorrow      = date( 'Y-m-d', strtotime($today.'+ 1 day')  ) ;
        //$week_from_now = date( 'Y-m-d', strtotime($today.'+ 7 days') );

        $booking_obj            = new Booking();
        $tomorrow_bookings      = $booking_obj->getAllBookingsByDay( $tomorrow );
        //$week_from_now_bookings = $booking_obj->getAllBookingsByDay( $week_from_now );

        return array(
                        'tomorrow_bookings' => $tomorrow_bookings,
                        //'week_from_now_bookings' => $week_from_now_bookings
                    );
    }

    public function sendMessages()
    {
        $bookings = self::getBookings();

        //Tomorrow
        $tomorrow_bookings = $bookings['tomorrow_bookings'];
        if( isset( $tomorrow_bookings->Bookings ) )
        {
            if( sizeof( $tomorrow_bookings->Bookings ) == 1 )
            {
                $tomorrow_bookings->Bookings = [$tomorrow_bookings->Bookings];
            }
            foreach ( $tomorrow_bookings->Bookings as $booking ) 
            {
                
                if( isset( $booking->Mobile ) && $booking->Mobile != '' )
                {
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

    public function replaceTemplateTags( $args )
    {        
        $output = '';
        $output  = $args['template'];

        $date      = date("D, d/m/Y", strtotime( $args['date'] ) );
        $time      = date("g:i a", strtotime( $args['time'] ) );
        $location  = $args['service_location'];
        $service_name  = $args['service_name'];
        $service_phone = $args['service_phone'];
        $client_name   = $args['client_name'];

        $output = str_replace( '(date)', $date, $output );
        $output = str_replace( '(time)', $time, $output );
        $output = str_replace( '(location)', $location, $output );
        $output = str_replace( '(client_name)', $client_name, $output );
        $output = str_replace( '(service_name)', $service_name, $output );
        $output = str_replace( '(service_phone)', $service_phone, $output );            
        
        return $output;
    }

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
            if( $service['BookingServiceId'] == $booking->ServiceId || $service['BookingInterpritterServiceId'] == $booking->ServiceId  )
            {
                $current_service = $service;
            }
        }

        $args['service_name']     = $current_service['ServiceName'];
        $args['service_location'] = $current_service['Location'];
        $args['service_phone']    = $current_service['Phone'];
        
        //SMS template
        $sms_template_obj = new SmsTemplate();
        $sms_template = $sms_template_obj->getTemplateByServiceId( $current_service['ServiceId'] );
        $args['template'] = $sms_template['Template'];
        
        //replace tags in template
        $template = self::replaceTemplateTags($args);

        //send sms
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