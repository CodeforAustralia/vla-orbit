<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Matter;
use App\Referral;
use App\ServiceProvider;
use App\Vulnerability;
use Auth;

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

        $user = auth()->user();

        $service_provider_obj  = new ServiceProvider();
        if ( $user->sp_id == 0)
        {
            $user->sp_id = 112;
        }
        $service_provider      = json_decode($service_provider_obj->getServiceProviderByID( $user->sp_id )['data'], true)[0];

        $referral_obj = new Referral($request);
        $response = $referral_obj->saveReferral( $request , $service_provider );

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
        if( empty( $question_list ) && $service_qty > 0)
        {
            return redirect('referral/create/result');
        }
        elseif( $service_qty > 0 )
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
        $service_providers_obj  = new ServiceProvider();
        $service_providers      = $service_providers_obj->getAllServiceProviders();

        if( $request->has('answers')  ) 
        {
            $answers = request('answers');
            $mt_id   = request('mt_id');

            $referral = new Referral();
            $matches  = $referral->filterByQuestions( $answers, $mt_id );     
                             
            if( sizeof($matches) > 0 )
            {
                return view( 'referral.create.result', compact( 'matches','service_providers' ) );
            }
        } 
        else {
            $service_qty = sizeof( session('matches') );
            if( $service_qty > 0 )
            {
                $matches = session('matches');
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
