<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EReferral;
use Auth;

class EReferralController extends Controller
{ 
    public function __construct()
    {       
        $this->middleware('auth');
    }
    
    public function index()
    {
        Auth::user()->authorizeRoles('Administrator');
        return view("e_referral.index");
    }

    public function show($erf_id)
    {
        Auth::user()->authorizeRoles('Administrator');
        $e_referral_obj = new EReferral();
        $e_referral = $e_referral_obj->getEReferralFormByID($erf_id)['data']->ReferralForm;        
        return view("e_referral.show", compact('e_referral'));
    }
    
    public function create()
    {
        Auth::user()->authorizeRoles('Administrator');
        return view("e_referral.create");
    }    

    public function store()
    {        
        Auth::user()->authorizeRoles('Administrator');
        $service_level_params =  array(
                                        'title'         => request('title'),
                                        'description'   => request('description'),
                                    );
        
        $e_referral = new EReferral();
        $response = $e_referral->saveEReferralForm(request()->all());
        
        return redirect('/e_referral')->with($response['success'], $response['message']);
    }

    public function destroy($erf_id)
    {
        Auth::user()->authorizeRoles('Administrator');
        $e_referral = new EReferral();
        $response = $e_referral->deleteEReferralForm($erf_id);
        
        return redirect('/e_referral')->with($response['success'], $response['message']);
    }
    
    public function list()
    {
        $e_referral_obj = new EReferral();
        $forms = $e_referral_obj->getAllEReferralForms();
        return ['data' => $forms ];
    }

    public function listFormsFormated()
    {
        $e_referral_obj = new EReferral();
        $forms = $e_referral_obj->getAllEReferralFormsFormated();
        return ['data' => $forms ];
    }
    
}