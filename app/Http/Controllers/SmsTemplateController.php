<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SmsTemplate;
use App\Service;
use Auth;

/**
 * SMS Template Controller.
 * Controller for the sms template messages functionalities
 * @author Christian Arevalo
 * @version 1.0.0
 * @see  Controller
 */
class SmsTemplateController extends Controller
{
    /**
     * SMS template contructor. Create a new instance
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of SMS template
     * @return view SMS template information
     */
    public function index()
    {
        return view("sms_template.index");
    }

    /**
     * Display a specific SMS template
     * @param integer $st_id SMS template id
     * @return view single SMS template information page
     */
    public function show( $st_id )
    {
        $sms_template_obj = new SmsTemplate();
        $templates = $sms_template_obj->getAllTemplates();

        $service_obj = new Service();
        $services = $service_obj->getAllServices();

        $current_sms_template = [];

        foreach ( $templates as $template ) {
        	if ( $template['TemplateId'] == $st_id ) {
        		$current_sms_template = $template;
        	}
        }

         usort($services, function($a, $b){ return strcmp($a["ServiceName"], $b["ServiceName"]); });

        return view("sms_template.show", compact( 'current_sms_template', 'services' ) );
    }

    /**
     * Store a newly or updated SMS template in the data base
     * @return mixed SMS template listing page with success/error message
     */
    public function store()
    {
        $user = Auth::user();

        $template = [
                        'CreatedBy' => $user->id,
                        'CreatedOn' => date("Y-m-d"),
                        'ServieId'  => request('sv_id'),
                        'Template'  => filter_var(request('template'), FILTER_SANITIZE_STRING),
                        'TemplateId'=> request('st_id')
        			];

        $sms_template_obj = new SmsTemplate();
		$response = $sms_template_obj->saveTemplates( $template );

        return redirect('/sms_template')->with($response['success'], $response['message']);
    }

   /**
     * Show the form for creating a new SMS template
     * @return view SMS template creation page
     */
    public function create()
    {
        $service_obj = new Service();
        $services = $service_obj->getAllServices();

        usort($services, function($a, $b){ return strcmp($a["ServiceName"], $b["ServiceName"]); });

        return view('sms_template.create', compact( 'services' ) );
    }

    /**
     * Remove the specified SMS template from data base.
     * @param  integer $st_id SMS template id
     * @return mixed SMS template listing page with success/error message
     */
    public function destroy( Request $request, $st_id )
    {
        $sms_template_obj = new SmsTemplate();
        $response = $sms_template_obj->deleteTemplateById( $st_id );

        return redirect('/sms_template')->with( $response['success'], $response['message'] );
    }

    /**
     * List all SMS template
     * @return array list of all SMS template
     */
    public function list()
    {
        $sms_template_obj = new SmsTemplate();
        return ['data' => $sms_template_obj->getAllTemplates() ];
    }
    /**
     * Get template by Booking Id
     *
     * @return void
     */
    public function getTemplateByServiceBookingId()
    {
        $sms_template_obj = new SmsTemplate();
        $sv_id = request('sv_id');
        $booking = request('booking');

        return $sms_template_obj->getTemplateByServiceBookingId($sv_id, $booking);
    }

}