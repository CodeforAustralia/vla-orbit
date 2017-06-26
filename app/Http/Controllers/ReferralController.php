<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Matter;
use App\Referral;
use App\Vulnerability;

class ReferralController extends Controller
{
    public function index()
    {
        return view("referral.index");
    }

    public function show()
    {
        return view("referral.show");
    }
    
    public function create()
    {
        return view("referral.create");
    }

    public function location()
    {
        return view("referral.create.location");
    }
    
    public function legal_issue()
    {
        $matter = new Matter();
        $matters = $matter->getAllMattersParentChildrenList();
        return view( "referral.create.legal_issue", compact( 'matters' ) );
    }
    
    public function details()
    {
        $vulnerability_obj = new Vulnerability();
        $vulnertability_questions = $vulnerability_obj->getAllVulnerabilityQuestions();

        return view( "referral.create.details", compact( 'vulnertability_questions' ) );
    }
    
    public function questions()
    {
        $ca_id  = isset( $_GET['ca_id'] )  ? $_GET['ca_id']  : '';
        $mt_id  = isset( $_GET['mt_id'] )  ? $_GET['mt_id']  : '';
        $vls_id = isset( $_GET['vls_id'] ) ? $_GET['vls_id'] : '';
        
        $referral = new Referral();
        $question_list = $referral->filterServices( $ca_id, $mt_id, $vls_id );
        return view( "referral.create.questions", compact('question_list') );
    }
    
    public function review()
    {
        return view("referral.create.review");
    }
    
    public function result()
    {
        $answers = request('answers');

        $referral = new Referral();
        $matches  = $referral->filterByQuestions( $answers );
        return view( "referral.create.result", compact('matches') );
    }
}
