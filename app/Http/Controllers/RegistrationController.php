<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Report;
use App\Role;
use App\User;
use Auth;
use SimpleSAML_Auth_Simple;

/**
 * Registration Controller.
 * Controller for the Orbit main page functionalities
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
        $this->middleware('guest', ['except' => ['index']]);
    }

    /**
     * Display a the orbit main page with the statistics
     * @return view orbit main page
     */
    public function index()
    {
        $user = Auth::user();

        $financial_year = date("Y");
        $last_monday =  date('Y-m-d',time()+( 1 - date('w'))*24*3600);
        $today = date('Y-m-d');
        $month = date('Y-m');

        $report_obj     = new Report();
        $stats          = $report_obj->getDashboadStats( $financial_year );
        $stats_today    = $report_obj->getDashboadStats( $today );
        $stats_month    = $report_obj->getDashboadStats( $month );
        $stats_week     = $report_obj->getDashboadStats( $last_monday );

        $referrals_made = 0;
        if( isset($user->id) )
        {
            $referrals_made = DB::table('logs')
                                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as referrals'))
                                ->where([
                                            ['object_type', 'referral'],
                                            ['user_id', $user->id]
                                        ])
                                ->groupBy('date')
                                ->get()
                                ->count();
        }

        $dashboards = \App\Dashboard::all()->sortBy('position');
        return view("orbit.index", compact( 'stats', 'stats_today', 'stats_month', 'stats_week', 'referrals_made', 'dashboards' ));
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
        if( request('sp_id') != 0 ) {
            $user->sp_id = request('sp_id'); //No service provider
        } else {
            $user->sp_id = 0; //No service provider
        }

        $user->save();
        auth()->login($user);

        return redirect('/');
    }

}
