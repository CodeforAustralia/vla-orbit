<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\SignupEmail;
use App\Report;
use App\BookingEngine;
use App\User;
use App\ServiceProvider;
use Auth;

/**
 * Registration Controller.
 * Controller for the main page functionalities
 * @author VLA & Code for Australia
 * @version 1.2.0
 * @see  Controller
 */
class RegistrationController extends Controller
{
    /**
     * Registration contructor. Create a new instance
     */
    public function __construct()
    {
        //$this->middleware('auth')->except(['create','show']);
        $this->middleware('guest', ['except' => ['index', 'information']]);
    }

    /**
     * Display a the orbit main page with the statistics
     * @return view orbit main page
     */
    public function index()
    {
        $user = Auth::user();

        $financial_year = date("Y");
        $year = date("Y-m-d", strtotime('first day of January'));
        $last_monday =  date("Y-m-d", strtotime('monday this week'));//date('Y-m-d',time()+( 1 - date('w'))*24*3600);
        $today = date('Y-m-d');
        $month = date('Y-m');

        $report_obj     = new Report();
        $booking_engine_obj = new BookingEngine();
        $stats          = $report_obj->getDashboadStats($financial_year);
        $stats_year     = (is_numeric($booking_engine_obj->getStatsPeriod($year)) ? $booking_engine_obj->getStatsPeriod($year) : '-');
        $stats_today    = (is_numeric($booking_engine_obj->getStatsDay($today)) ? $booking_engine_obj->getStatsDay($today) : '-');
        $stats_month    = $report_obj->getDashboadStats($month);
        $stats_week     = (is_numeric($booking_engine_obj->getStatsPeriod($last_monday)) ? $booking_engine_obj->getStatsPeriod($last_monday) : '-');

        $referrals_made = 0;
        if (isset($user->id)) {
            $referrals_made = DB::table('logs')
                                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as referrals'))
                                ->where([
                                            ['object_type', 'referral'],
                                            ['user_id', $user->id]
                                        ])
                                ->groupBy('date')
                                ->get()
                                ->count();
            // get all service providers
            $service_providers_obj  = new ServiceProvider();
            $service_providers      = $service_providers_obj->getAllServiceProviders();
        }

        $dashboards = \App\Dashboard::all()->sortBy('position');
        return view(
            "orbit.index",
            compact(
                'stats',
                'stats_today',
                'stats_month',
                'stats_week',
                'stats_year',
                'referrals_made',
                'dashboards',
                'service_providers'
            )
        );
    }

    /**
     * Display a the orbit main page with general information and contact details
     * @return view orbit information page
     */
    public function information()
    {
        return view("orbit.information");
    }

    public function contact()
    {
        // Validate the form
        $this->validate(
            request(),
            [
                                'name' => 'required',
                                'email' => 'required|email',
                                'message' => 'required'
                            ]
        );
        $app_name = strtoupper(config('app.name'));
        $args['Message'] = 'Thanks for showing interest in '. $app_name .'. Please fill in your details below and an '. $app_name .' team member will get in touch shortly.<br><br>

                            Name:' . request('name') .'<br>
                            Email address:' . request('email') .'<br>
                            Message:' . request('message') ;

        Mail::to(config('app.team_email'))->send(new SignupEmail($args));
    }

    /**
     * Page to create users
     * @return view registration page
     */
    public function create()
    {
        return view("registration.create");
    }

    /**
     * Store a newly user in the data base
     * @return view orbit main page
     */
    public function store()
    {
        // Validate the form
        $this->validate(
            request(),
            [
                                'name' => 'required',
                                'email' => 'required|unique:users|email',
                                'password' => 'required|confirmed'
                            ]
        );

        //create and save the user
        $user = User::create([
                                'name' => request('name'),
                                'email' => request('email'),
                                'password' => bcrypt(request('password'))
                            ]);

        //sign them in and add role
        if (request('sp_id') != 0) {
            $user->sp_id = request('sp_id'); //No service provider
        } else {
            $user->sp_id = 0; //No service provider
        }

        $user->save();
        auth()->login($user);

        return redirect('/');
    }
}
