<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use SimpleSAML_Auth_Simple;

class ReferralController extends Controller
{
    public function index()
    {
        /*           
        $as = new SimpleSAML_Auth_Simple(env('SIMPLESML_SP'));
        $as->requireAuth();
        $attributes = $as->getAttributes();
        
        dd($attributes);
        */
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
}
