<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Matter;
use App\Referral;
use App\ServiceProvider;
use App\Vulnerability;

class ReferralController extends Controller
{
    public function __construct()
    {       
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view('referral.index');
    }

    public function show()
    {
        return view('referral.show');
    }
    
    public function store()
    {
        $request = request()->all();       

        $referral_obj = new Referral($request);
        $response = $referral_obj->saveReferral( $request );

        return $response;

    }

    public function create()
    {
        return view('referral.create');
    }

    public function location()
    {
        return view('referral.create.location');
    }
    
    public function legal_issue()
    {
        $matter = new Matter();
        $matters = $matter->getAllMattersParentChildrenList();
        return view( 'referral.create.legal_issue', compact( 'matters' ) );
    }
    
    public function details()
    {
        $ca_id  = isset( $_GET['ca_id'] )  ? $_GET['ca_id']  : '';
        $mt_id  = isset( $_GET['mt_id'] )  ? $_GET['mt_id']  : '';
        
        $referral = new Referral();
        $vulnertability_info = $referral->getVulnerabilityByServices( $ca_id, $mt_id );

        $vulnertability_questions = $vulnertability_info['vulnertability_questions'];
        $service_qty = $vulnertability_info['service_qty'];

        if( $service_qty > 0)
        {
            return view( 'referral.create.details', compact( 'vulnertability_questions', 'service_qty' ) );
        }
        else{
            return view( 'referral.create.no-results' );
        }

    }
    
    public function questions()
    {
        $ca_id  = isset( $_GET['ca_id'] )  ? $_GET['ca_id']  : '';
        $mt_id  = isset( $_GET['mt_id'] )  ? $_GET['mt_id']  : '';
        $vls_id = isset( $_GET['vls_id'] ) ? $_GET['vls_id'] : '';
        
        $referral = new Referral();
        $question_list = $referral->filterServices( $ca_id, $mt_id, $vls_id );
        
        $service_qty = sizeof( session('matches') );
        if( $service_qty > 0 )
        {
            return view( 'referral.create.questions', compact( 'question_list', 'service_qty' ) );
        }
        else{
            return view( 'referral.create.no-results' );
        }        

    }
    
    public function review()
    {
        return view('referral.create.review');
    }
    
    public function result( Request $request )
    {
        if( $request->has('answers')  ) 
        {
            $answers = request('answers');

            $service_providers_obj  = new ServiceProvider();
            $service_providers      = $service_providers_obj->getAllServiceProviders();

            $referral = new Referral();
            $matches  = $referral->filterByQuestions( $answers );
            if( sizeof($matches) > 0 )
            {
                return view( 'referral.create.result', compact( 'matches','service_providers' ) );
            }
        }
        return view( 'referral.create.no-results');
    }

    public function list()
    {
        
        $referral_obj = new Referral();
        return ['data' => $referral_obj->getAllReferrals() ];
    }
}
