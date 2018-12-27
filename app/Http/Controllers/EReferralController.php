<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EReferral;
use Auth;

/**
 * EReferral Controller.
 * Controller for the EReferral process
 *
 * @author Christian Arevalo
 * @version 1.2.0
 * @see Controller
 */
class EReferralController extends Controller
{
    /**
     * EReferral constructor. Create a new instance
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of EReferral
     * @return view EReferral information
     */
    public function index()
    {
        Auth::user()->authorizeRoles('Administrator');

        return view("e_referral.index");
    }
    /**
     * Display a specific EReferral
     * @return view single EReferral information page
     */
    public function show($erf_id)
    {
        Auth::user()->authorizeRoles('Administrator');
        $e_referral_obj = new EReferral();
        $e_referral = $e_referral_obj->getEReferralFormByID($erf_id)['data']->ReferralForm;
        $fields = ( isset( $e_referral->Fields ) ?  explode(',', $e_referral->Fields) : [] );

        return view("e_referral.show", compact('e_referral', 'fields'));
    }
    /**
     * Show the form for creating a new EReferral
     * @return view EReferral creation page
     */
    public function create()
    {
        Auth::user()->authorizeRoles('Administrator');
        return view("e_referral.create");
    }
    /**
     * Store a newly or updated EReferral in the data base
     * @return mixed  EReferal listing page with success/error message
     */
    public function store()
    {
        Auth::user()->authorizeRoles('Administrator');

        $e_referral = new EReferral();
        $response = $e_referral->saveEReferralForm(request()->all());

        return redirect('/e_referral')->with($response['success'], $response['message']);
    }
    /**
     * Remove the specified EReferral from data base.
     * @param  int $erf_id EReferral Id
     * @return mixed EReferal listing page with success/error message
     */
    public function destroy($erf_id)
    {
        Auth::user()->authorizeRoles('Administrator');
        $e_referral = new EReferral();
        $response = $e_referral->deleteEReferralForm($erf_id);

        return redirect('/e_referral')->with($response['success'], $response['message']);
    }
    /**
     * List all EReferral
     * @return array list of all EReferral
     */
    public function list()
    {
        $e_referral_obj = new EReferral();
        $forms = $e_referral_obj->getAllEReferralForms();

        return ['data' => $forms ];
    }
    /**
     * list all EReferral formated
     * @return array list of all EReferral formated
     */
    public function listFormsFormated()
    {
        $e_referral_obj = new EReferral();
        $forms = $e_referral_obj->getAllEReferralFormsFormated();

        return ['data' => $forms ];
    }

    /**
     * List EReferral By ID
     * @return array information of specific EReferral
     */
    public function listById($erf_id)
    {
        $e_referral_obj = new EReferral();
        $e_referral = $e_referral_obj->getEReferralFormByID($erf_id)['data']->ReferralForm;

        return json_encode($e_referral);
    }
}