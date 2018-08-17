<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Service;
use App\ServiceBooking;
use App\ServiceProvider;
use Auth;
use DateTime;
use DateInterval;
use DatePeriod;

/**
 * Booking Engine Controller.
 * Controller for the booking functionalities
 * @author Christian Arevalo
 * @version 1.2.0
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
}