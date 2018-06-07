<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Report;
use App\Statistic;
use Auth;

/**
 * Statistic Controller.
 * Controller for the statistics functionalities
 * @author Christian Arevalo
 * @version 1.0.0
 * @see  Controller
 */
class StatisticController extends Controller
{
    /**
     * statistic contructor. Create a new instance
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display an informatin page about ORBIT statistics
     * @return view statistic information
     */
    public function index()
    {

        Auth::user()->authorizeRoles('Administrator');

        $financial_year = date("Y");
        $last_monday =  date('Y-m-d',time()+( 1 - date('w'))*24*3600);
        $today = date('Y-m-d');
        $month = date('Y-m');
        $previous_month = date('Y-m', strtotime(date('Y-m')." -1 month"));

        $report_obj     = new Report();
        $stats          = $report_obj->getDashboadStats( $financial_year );
        $stats_today    = $report_obj->getDashboadStats( $today );
        $stats_month    = $report_obj->getDashboadStats( $month );
        $stats_week     = $report_obj->getDashboadStats( $last_monday );
        $stats_previous_month = $report_obj->getDashboadStats( $previous_month );

        $bookings = [
                        'year'  => $stats->NoOfBookingThisYear,
                        'month' => $stats_month->NoOfBookingThisMonth,
                        'previous_month'  => $stats_previous_month->NoOfBookingThisMonth,
                        'total_referrals' => $stats->NoOfReferrals
                    ];
        return view( "statistic.index", compact( 'bookings' ) );
    }

    /**
     * List all statistic
     * @return array list of all statistic
     */
    public function listStatistics( )
    {
    	$statistic_obj = new Statistic();

    	return  [
    				'BookinsPerService' => $statistic_obj->getOrbitBookinsPerServiceasJSON(),
    				'BookinsAvgTime' 	=> $statistic_obj->getOrbitBookinsAvgTimeasJSON()
    			];
    }

}