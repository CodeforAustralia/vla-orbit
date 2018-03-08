<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\NoReplyEmail;
use App\ServiceProvider;

class NoReplyEmailController extends Controller
{    
    public function __construct()
    {       
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view("no_reply_emails.index");
    }
    
    public function show( $te_id )
    {       
        $nre_obj = new NoReplyEmail(); 
        $template = $nre_obj->getTemplateById( $te_id );

        $sp_obj = new ServiceProvider();
        $service_providers = $sp_obj->getAllServiceProviders();

        usort($service_providers, function($a, $b){ return strcasecmp($a["ServiceProviderName"], $b["ServiceProviderName"]); });

        return view( "no_reply_emails.show_template", compact('template', 'service_providers') );
    }
    
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
    
    public function destroyTemplate( Request $request, $te_id )
    {        
        $request->user()->authorizeRoles('Administrator');

        $nre_obj = new NoReplyEmail(); 
        $response = $nre_obj->deleteTemplate( $te_id );
        
        return redirect('/no_reply_emails/templates')->with( $response['success'], $response['message'] );
    }


    public function indexTemplates()
    {
        return view("no_reply_emails.index_templates");
    }

    public function createTemplate()
    {
        $sp_obj = new ServiceProvider();
        $service_providers = $sp_obj->getAllServiceProviders();

        usort($service_providers, function($a, $b){ return strcasecmp($a["ServiceProviderName"], $b["ServiceProviderName"]); }); 
        
        return view("no_reply_emails.create_template", compact( 'service_providers' ) );
    }

    public function listAllTemplates()
    {
        $nre_obj = new NoReplyEmail(); 
        return $nre_obj->getAllTemplates();
    }

    public function listTemplateById()
    {
        $template_id = request('template_id');
        $nre_obj = new NoReplyEmail(); 
        return $nre_obj->getTemplateById( $template_id );        
    }

    public function listAllTemplatesBySection()
    {
        $nre_obj = new NoReplyEmail(); 
        return $nre_obj->getAllTemplatesBySection();
    }

    public function listAllLogRecords()
    {
        $nre_obj = new NoReplyEmail(); 
        return $nre_obj->getAllLogRecords();
    }

    public function listAllMailBoxes()
    {
        $nre_obj = new NoReplyEmail(); 
        return $nre_obj->getAllMailBoxes();
    }

    public function sendEmail()
    {
        $nre_obj = new NoReplyEmail(); 
        $response = $nre_obj->sendEmail( request()->all() );  
        
        return redirect('/no_reply_emails')->with($response['success'], $response['message']);          
    }

    public function saveTemplate()
    {
        $nre_obj = new NoReplyEmail();
        $response = $nre_obj->saveEmailTemplate( request()->all() );
        return redirect('/no_reply_emails/templates')->with($response['success'], $response['message']);    
    }
    /**
     * List all templates formated for select2
     * @return array array of all templates formated
     */
    public function listAllTemplatesFormated()
    {
        $nre_obj = new NoReplyEmail();        
        $result = $nre_obj->getAllTemplatesFormatedBySection();
        return $result;
    }    
}
