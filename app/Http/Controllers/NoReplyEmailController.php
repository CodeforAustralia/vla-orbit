<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\NoReplyEmail;
use App\ServiceProvider;
use Auth;

/**
 * No Reply Email Controller.
 * Controller for the information email and template functionalities
 *   
 * @author VLA & Code for Australia
 * @version 1.2.0
 * @see  Controller
 */
class NoReplyEmailController extends Controller
{   
    /**
     * No reply email contructor. Create a new instance
     */
    public function __construct()
    {       
        $this->middleware('auth');
    }
    /**
     * Display a listing of no reply email
     * @return view no replay email information
     */    
    public function index()
    {
        return view("no_reply_emails.index");
    }
    /**
     * Display a specific no replay email template
     * @param  int  $te_id    template Id
     * @return view single template information page
     */      
    public function show( $te_id )
    {       
        $nre_obj = new NoReplyEmail(); 
        $template = $nre_obj->getTemplateById( $te_id );

        $sp_obj = new ServiceProvider();
        $service_providers = $sp_obj->getAllServiceProviders();

        usort($service_providers, function($a, $b){ return strcasecmp($a["ServiceProviderName"], $b["ServiceProviderName"]); });

        return view( "no_reply_emails.show_template", compact('template', 'service_providers') );
    }
    /**
     * Show the form for creating a new no reply email
     * @return view no reply email creation page
     */     
    public function create()
    {        
        $nre_obj = new NoReplyEmail();
        $section = 'All';
        if( auth()->user()->sp_id != 0)
        {
            $section = $nre_obj->getSection();
        } 
        $templates = $nre_obj->getAllTemplatesBySection()['data'];                
        usort($templates, function($a, $b){ return strcasecmp($a["Name"], $b["Name"]); });
        return view( "no_reply_emails.create", compact('templates', 'section') );
    }
    /**
     * Remove the specified no replay email template from data base.
     * @param  int $te_id template Id
     * @param  Request $request
     * @return mixed legal matter listing page with success/error message
     */    
    public function destroyTemplate( Request $request, $te_id )
    {        
        Auth::user()->authorizeRoles( ['Administrator', 'AdminSp', 'AdminSpClc'] );

        $nre_obj = new NoReplyEmail(); 
        $response = $nre_obj->deleteTemplate( $te_id );
        
        return redirect('/no_reply_emails/templates')->with( $response['success'], $response['message'] );
    }

    /**
     * Display a listing of no reply email template
     * @return view no replay email template information
     */  
    public function indexTemplates()
    {
        return view("no_reply_emails.index_templates");
    }
    /**
     * Show the form for creating a new no reply email template
     * @return view no reply email template creation page
     */     
    public function createTemplate()
    {
        $sp_obj = new ServiceProvider();
        $service_providers = $sp_obj->getAllServiceProviders();

        usort($service_providers, function($a, $b){ return strcasecmp($a["ServiceProviderName"], $b["ServiceProviderName"]); }); 
        
        return view("no_reply_emails.create_template", compact( 'service_providers' ) );
    }
    /**
     * List all no reply email templates
     * @return array list of all no reply email templates
     */
    public function listAllTemplates()
    {
        $nre_obj = new NoReplyEmail(); 
        return $nre_obj->getAllTemplates();
    }
    /**
     * Get specific no reply email template
     * @return Object specific no reply template
     */
    public function listTemplateById()
    {
        $template_id = request('template_id');
        $nre_obj = new NoReplyEmail(); 
        return $nre_obj->getTemplateById( $template_id );        
    }
    /**
     * List all no reply email templates filtered by section
     * @return array list of all no reply email templates filtered
     */
    public function listAllTemplatesBySection()
    {
        $nre_obj = new NoReplyEmail(); 
        return $nre_obj->getAllTemplatesBySection();
    }
    /**
     * List all no reply email
     * @return array list of all no reply email
     */
    public function listAllLogRecords()
    {
        $nre_obj = new NoReplyEmail(); 
        return $nre_obj->getAllLogRecords();
    }
    /**
     * List all mail boxex
     * @return array list of all mail boxes
     */
    public function listAllMailBoxes()
    {
        $nre_obj = new NoReplyEmail(); 
        return $nre_obj->getAllMailBoxes();
    }
    /**
     * Send a new no reply email
     * @return mixed no replay email page with success/error message
     */
    public function sendEmail()
    {
        $nre_obj = new NoReplyEmail(); 
        $response = $nre_obj->sendEmail( request()->all() );  
        
        return redirect('/no_reply_emails')->with($response['success'], $response['message']);          
    }
    /**
     * Store a newly or updated no reply email template in the data base
     * @return mixed no reply email template listing page with success/error message
     */ 
    public function saveTemplate()
    {
        $nre_obj = new NoReplyEmail();
        $response = $nre_obj->saveEmailTemplate( request()->all() );
        return redirect('/no_reply_emails/templates')->with($response['success'], $response['message']);    
    }
    /**
     * List all no reply templates formated for select2
     * @return array list of all templates formated
     */
    public function listAllTemplatesFormated()
    {
        $nre_obj = new NoReplyEmail();        
        $result = $nre_obj->getAllTemplatesFormatedBySection();
        return $result;
    } 

    /**
     * Get All sent no reply emails by section
     * @return array list sent emails filtered by section
     */
    public function getAllLogRecordBySection()
    {
        $nre_obj = new NoReplyEmail();
        $result = $nre_obj->getAllLogRecordBySection();
        return $result; 
    }
}
