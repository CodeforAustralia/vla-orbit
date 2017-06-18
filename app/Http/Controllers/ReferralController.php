<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Matter;

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
        return view("referral.create.details");
    }
    
    public function questions()
    {
        return view("referral.create.questions");
    }
    
    public function review()
    {
        return view("referral.create.review");
    }
    
    public function result()
    {
        return view("referral.result");
    }
}
