<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BookingEngine;
use App\Service;
use App\ServiceBooking;
use App\ServiceProvider;
use App\SentSms;
use Auth;
use DateTime;
use DateInterval;
use DatePeriod;

use Validator;

/**
 * Booking Engine Controller.
 * Controller for the booking functionalities
 * @author Christian Arevalo
 * @version 1.3.0
 * @see  Controller
 */
class BookingEngineController extends Controller
{
    /**
     * Booking contructor. Create a new instance
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a list of services by service provider that the user belongs
     * @return view booking information
     */
    public function index()
    {
        $service_providers_obj  = new ServiceProvider();
        $service_providers      = $service_providers_obj->getAllServiceProviders();

        $user = Auth::user();
        $sp_id = $user->sp_id;
        $sp_id = 119;
        $service_obj = new Service();
        if($sp_id > 0) {
            $services = $service_obj->getAllServicesBySP( $sp_id );
        } else {
            $services = $service_obj->getAllServices();
        }
        $calendars = self::generateCalendars();
        return view("booking.engine.index", compact('services', 'service_providers', 'calendars'));
    }

    /**
     * Display a list of resources by service provider that the user belongs
     * @return view booking resource information
     */
    public function resources()
    {
        $user = Auth::user();
        $sp_id = $user->sp_id;

        $calendars = self::generateCalendars();
        return view("booking.engine.resources", compact('calendars'));
    }

    /**
     * Display a list of services  -  REMOVE
     * @return json services information
     */
    public function listServices()
    {
        $user = Auth::user();
        $sp_id = $user->sp_id;
        $service_obj = new Service();
        if($sp_id > 0) {
            $services = $service_obj->getAllServicesBySP( $sp_id );
        } else {
            $services = $service_obj->getAllServices();
        }

        return $services;
    }

    public function generateCalendars()
    {
        $current_year = date('Y');
        $next_year = date('Y', strtotime('+1 year'));

        $calendar_current_year = self::generateCalendarByYear($current_year);
        $calendar_next_year = self::generateCalendarByYear($next_year);

        return  [
                    'current_year' => $calendar_current_year,
                    'next_year' => $calendar_next_year
                ];
    }

    public function generateCalendarByYear($year)
    {
        $days = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
        $start_time = new DateTime("first day of January ". $year);
        $end_time = new DateTime("first day of January  ". ($year +1) );

        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($start_time, $interval, $end_time);

        foreach ($period as $dt) {
            $day_number = $dt->format('d');
            $month_name = $dt->format('M');

            if( $day_number == 1 ) { //Check if is the first day of the month
                $day_pos = array_search( $dt->format('D') , $days); // Check the positon of day in array as we start a week on Monday
                for($j=0; $j< $day_pos; $j++) {
                    $list[$month_name][$j] = ""; //Add empty values while it find the week day to set the 1st day of the month
                }
            }
            $list[$month_name][$day_pos] = $dt->format('d');
            $day_pos++;
        }
        return $list;
    }

    public function getServiceDays()
    {
        $sv_id = request('sv_id');

        $calendars = self::generateCalendars();

        $calendars['selected_current'] = self::generateCalendar();
        $calendars['selected_current_interpreter'] = self::generateCalendar();
        $calendars['selected_next'] = self::generateCalendar();
        $calendars['selected_next_interpreter'] = self::generateCalendar();

        return $calendars;
    }

    public function generateCalendar()
    {
        $calendar = [];
        $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        $iterations = rand(1,10);
        for($i = 1 ; $i <= $iterations; $i++) {
            $month = rand(0,11);
            $day = sprintf('%02d', rand(1,31));
            $calendar[] = $months[$month] . '-' . $day;
        }
        return $calendar;
    }

    public function getServiceHours()
    {
        $sv_id = request('sv_id');
        return self::generateHours();
    }

    public function generateHours()
    {
        $hour_lenght = 30;
        $schedule['regular'] = [
            'time_name' => 'half_hour',
            'time_lenght' => $hour_lenght,
            'days' => self::generateHour($hour_lenght)
        ];
        $hour_lenght = 60;
        $schedule['interpreter'] = [
            'time_name' => 'hour',
            'time_lenght' => 60,
            'days' => self::generateHour($hour_lenght)
        ];
        return $schedule;
    }

    public function generateHour($lenght)
    {
        $hours = [];
        $days = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
        $iterations = rand(1,10);
        for($i = 1 ; $i <= $iterations; $i++) {
            $week = rand(0,6);
            $day = rand(0,23) * $lenght;
            $hours[] = $days[$week] . '-' . $day;
        }
        return $hours;
    }

    /**
     * Get service availability in Booking Engine
     *
     * @param Request $request
     * @return void
     */
    public function getServiceAvailability( Request $request)
    {
        $validation = $this->getAvailabilityData($request->route()->parameters());
        if(!$validation->fails()) {
            $booking_engine_obj = new BookingEngine();
            return json_encode($booking_engine_obj->getServiceAvailability($request->route()->parameters()));
        } else {
            return response()->json(['error'=>$validation->errors()]);
        }

    }

    /**
     * Get the booking by service and date
     *
     * @param Request $request
     * @return void
     */
    public function getServiceBookings( Request $request )
    {
        $validation = $this->getBookingsData($request->route()->parameters());
        if(!$validation->fails()) {
            $booking_engine_obj = new BookingEngine();
            return $booking_engine_obj->getServiceBookings($request->route()->parameters());
        } else {
            return response()->json(['error'=>$validation->errors()]);
        }

    }
    /**
     * Get all booking by service provider and date
     *
     * @param Request $request
     * @return void
     */
    public function getServiceBookingsBySP( Request $request )
    {
        $validation = $this->getBookingsBySPData($request);
        if(!$validation->fails() && isset($request['sp_id'])) {
            $booking_engine_obj = new BookingEngine();
            $args = [
                'sp_id' => $request['sp_id'],
                'start_date' => $request['start'],
                'end_date'   => $request['end']
            ];
            $bookings = $booking_engine_obj->getServiceBookingsBySP($args);

            // Include the SMS information in Bookings
            $bookings['bookings'] = self::getSentSMSDates($bookings['bookings']);

            return $bookings;
        } else {
            return response()->json(['error'=>$validation->errors()]);
        }

    }
    /**
     * Get Sms dates for a collection of bookings
     *
     * @param array $bookings
     * @return void
     */
    private function getSentSMSDates($bookings)
    {
        $sent_sms_obj = new SentSms();
        $replace = ["/Date(", ")/"];
        $date = new DateTime();
        foreach ($bookings as $key => $booking) {
            $date_array = [];
            $sms_date = '';
            $date = new DateTime();
            $sms_messages = $sent_sms_obj->getSentSMSByBookingRefID($booking->id);
            foreach ($sms_messages as $key => $sms_message) {
                $sms_date = str_replace($replace,"", $sms_message['SentDate']);
                $date->setTimestamp(intval(substr($sms_date, 0, 10)));
                $date_formatted = $date->format('Y-m-d');
                $date_array[] = $date_formatted;
            }
            $booking->sms_date =  implode (", ", $date_array);
        }
        return $bookings;
    }

    /**
     * Get All Booking Status
     *
     * @return void
     */
    public function getAllBookingStatus()
    {
        $booking_engine_obj = new BookingEngine();
        return $booking_engine_obj->getAllBookingStatus();
    }

    public function deleteBooking($booking_id)
    {
        $booking_engine_obj = new BookingEngine();
        $response = $booking_engine_obj->deleteBooking($booking_id);
        $message = 'Error, Consult with the admin';
        if($response) {
            $success = 'success';
            $message = 'sucess,  the booking ' .$booking_id . ' has been deleted';
        }
        return $message;

    }

    /**
     * Get the request's data from the request.
     *
     * @param array $request
     * @return array
     */
    protected function getBookingsData($request)
    {
        $rules = [
            'service_id' => 'required',
            'start_date' => 'required',
            'end_date'   => 'required'
        ];

        $validator = Validator::make($request, $rules);
        return $validator;
    }
    /**
     * Get the request's data from the request.
     *
     * @param array $request
     * @return array
     */
    protected function getBookingsBySPData($request)
    {
        $rules = [
            'sp_id' => 'required',
            'start' => 'required',
            'end'   => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        return $validator;
    }
    /**
     * validate get availability data
     *
     * @param Request $request
     * @return void
     */
    protected function getAvailabilityData($request)
    {
        $rules = [
            'sv_id' => 'required',
            'start_date' => 'required',
            'end_date'   => 'required'
        ];

        $validator = Validator::make($request, $rules);
        return $validator;
    }

}