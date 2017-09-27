<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SmsTemplate;
use App\Service;
use Auth;

class SmsTemplateController extends Controller
{
    public function __construct()
    {       
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view("sms_template.index");
    }    

    public function show( $st_id )
    {
        $sms_template_obj = new SmsTemplate();
        $templates = $sms_template_obj->getAllTemplates();

        $service_obj = new Service();
        $services = $service_obj->getAllServices();

        $current_sms_template = [];

        foreach ($templates as $template) 
        {
        	if( $template['TemplateId'] == $st_id )
        	{
        		$current_sms_template = $template;
        	}
        }

         usort($services, function($a, $b){ return strcmp($a["ServiceName"], $b["ServiceName"]); }); 

        return view("sms_template.show", compact( 'current_sms_template', 'services' ) );
    }
    
    public function store()
    {
        $user = Auth::user();

        $template = array(
        					'CreatedBy' => $user->id,
        					'CreatedOn' => date("Y-m-d"),
        					'ServieId'  => request('sv_id'),
        					'Template'  => request('template'),
        					'TemplateId'=> request('st_id')
        				  );

        $sms_template_obj = new SmsTemplate();
		$response = $sms_template_obj->saveTemplates( $template );
		
        return redirect('/sms_template')->with($response['success'], $response['message']);

    }
    public function create()
    {
        $service_obj = new Service();
        $services = $service_obj->getAllServices();        

        usort($services, function($a, $b){ return strcmp($a["ServiceName"], $b["ServiceName"]); }); 

        return view('sms_template.create', compact( 'services' ) );
    }

    public function destroy( Request $request, $st_id )
    {        
        $sms_template_obj = new SmsTemplate();
        $response = $sms_template_obj->deleteTemplateById( $st_id );
        
        return redirect('/sms_template')->with( $response['success'], $response['message'] );
    }

    public function list()
    {        
        $sms_template_obj = new SmsTemplate();
        return ['data' => $sms_template_obj->getAllTemplates() ];
    }
}