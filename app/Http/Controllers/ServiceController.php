<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service;
use App\ServiceAction;
use App\ServiceType;
use App\ServiceLevel;
use App\ServiceProvider;
use App\Matter;
use App\MatterService;
use App\Catchment;
use App\Vulnerability;
use App\MatterServiceAnswer;
use Auth;

class ServiceController extends Controller
{    
    public function __construct()
    {       
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view("service.index");    
    }

    public function show( $sv_id )
    {
        $user = Auth::user();

        $service_type_obj   = new ServiceType();
        $service_types      = $service_type_obj->getAllServiceTypes();

        $service_level_obj  = new ServiceLevel();
        $service_levels     = $service_level_obj->getAllServiceLevels();

        $service_provider_obj   = new ServiceProvider();
        $service_providers      = $service_provider_obj->getAllServiceproviders();

        $matter_type_obj    = new Matter();
        $matters            = $matter_type_obj->getAllMatters();

        $matter_service_obj = new MatterService();
        $matter_services_list    = $matter_service_obj->getMatterServiceBySvID($sv_id);    
        $matter_services =	[];

        foreach ($matter_services_list as $matter_service) {
           	$matter_services[] = $matter_service->MatterId;
       	}   

        $service = new Service();
        $result  = $service->getServiceByID($sv_id);

        $vulnerability_obj = new Vulnerability();
        $vulnertability_questions = $vulnerability_obj->getAllVulnerabilityQuestions();

        if(isset($result['data'])) {
        	$current_service = json_decode( $result['data'] )[0];
            $current_vulnerabilities = array_column($current_service->ServiceVulAnswers, 'QuestionId');

            $catchment = new Catchment();
            $catchments = $catchment->sortCatchments( $current_service->ServiceCatchments );

            $referral_conditions = [];
            $booking_conditions = [];
            foreach($current_service->ServiceActions as $action)
            {
                if( $action->Action === 'REFER' )
                {
                    $referral_conditions[] = $action->ServiceProviderId;
                }
                if( $action->Action === 'BOOK' )
                {
                    $booking_conditions[] = $action->ServiceProviderId;
                }
            }

            if( $user->sp_id == $current_service->ServiceProviderId || $user->roles()->first()->name == 'Administrator' ) //Same service provider or admin
            {
                return view( "service.show", compact( 'current_service', 'service_types', 'service_levels', 'service_providers', 'matters', 'matter_services' , 'catchments', 'vulnertability_questions', 'current_vulnerabilities', 'referral_conditions', 'booking_conditions' ) ); 
            }
            else
            {                
                abort(401, 'This action is unauthorized.');
            }
       	
        } else {
        	return redirect('/service')->with( $result['success'], $result['message'] );
        }        
    }
    

    public function store()
    {
        Auth::user()->authorizeRoles( ['Administrator', 'AdminSp', 'AdminSpClc'] );

        $sv_params = array(                    
                            'ServiceId'   	=> request('sv_id'),
                            'ServiceName'   => request('name'),
                            'Phone'         => request('phone'),
                            'Email'         => request('email'),
                            'Description'   => request('description'),
                            'Location'      => request('location'),
                            'URL'           => request('URL'),
                            'ServiceProviderId' => request('service_provider_id'), 
                            'Wait'           => request('wait'),
                            'ServiceLevelId' => request('service_level_id'),
                            'ServiceTypeId'  => request('service_type_id'),
                            'OpenningHrs'    => request('OpenningHrs'),
                            'Status'         => ( request('Status') == 'on' ? 1 : 0 ),
                            'Specialist'     => false,
                            );
        
        $service = new Service();
        $response = $service->saveService( $sv_params );        

        if( isset( $response['data'] ) || request('sv_id') > 0 ){

        	$sv_id = ( request('sv_id') > 0 ) ? request('sv_id') : $response['data'];

        	$matter_service_obj = new MatterService();      
            $matter_service_obj->deleteMatterServiceByID( $sv_id ) ;	
       	    
            if( !empty( request('matters') ) )
            {
                foreach (request('matters') as $value) {                            
                    $mt_id  = $value;   
                    $result = $matter_service_obj->saveMatterService( $sv_id, $mt_id );             
                }
            }

            $catchment = new Catchment();
            $catchment->setCatchmentsOnRequest( request()->all(), $sv_id );
         
            $vulnerability_obj = new Vulnerability();
            // Delete previous vul. answers
            $vulnerability_obj->deleteVulnerabilityByServiceID( $sv_id ); 
            if( !empty( request('vulnerability') ) ) 
            {
                $vul_questions = array_keys( request('vulnerability') );          
                // Save vul. answers          
                $vulnerability_obj->saveVulnerabilityQuestions( $sv_id, $vul_questions );                
            }

            if( !empty( request('question') ) )
            {
                $matter_service_answer = new MatterServiceAnswer();            
                $matter_service_answer->processMatterServiceAnswer( request('question'), $sv_id ); 
                $matter_service_answer->processVulnerabilityMatterServiceAnswer( request('vulnerability_matter'), $sv_id );
            }

            $service_action = new ServiceAction();
            $service_action->deleteAllActionsByService( $sv_id ) ;
            $service_action->saveServiceAction( 'REFER', $sv_id, request('referral_conditions') ) ;
            $service_action->saveServiceAction( 'BOOK', $sv_id, request('booking_conditions') ) ;
        }
        
        return redirect('/service')->with( $response['success'], $response['message'] );
    }
    
    public function create()
    {
        Auth::user()->authorizeRoles( ['Administrator', 'AdminSp', 'AdminSpClc'] );

        $service_type_obj   = new ServiceType();
        $service_types      = $service_type_obj->getAllServiceTypes();

        $service_level_obj  = new ServiceLevel();
        $service_levels     = $service_level_obj->getAllServiceLevels();

        $service_provider_obj   = new ServiceProvider();
        $service_providers      = $service_provider_obj->getAllServiceproviders();

        $matter_type_obj    = new Matter();
        $matters            = $matter_type_obj->getAllMatters();

        $vulnerability_obj = new Vulnerability();
        $vulnertability_questions = $vulnerability_obj->getAllVulnerabilityQuestions();

        $user = Auth::user();
        if($user->sp_id != 0) //Just return current service provider
        {
            $service_provider = $service_providers[array_search($user->sp_id, array_column($service_providers, 'ServiceProviderId'))];            
            $service_providers = [$service_provider]; 
        }
        return view( "service.create", compact( 'service_types','service_levels','service_providers', 'matters', 'vulnertability_questions' ) );
    }

    public function destroy($sv_id)
    {
        Auth::user()->authorizeRoles( 'Administrator' );

        $service  = new Service();
        $response = $service->deleteService($sv_id);
        
        return redirect('/service')->with( $response['success'], $response['message'] );
    }

    public function list()
    {
        $service = new Service();
        $result  = $service->getAllServices();
        return array( 'data' => $result );
    }

    public function listServicesSP( $sp_id )
    {
        $user = Auth::user();
        
        $service = new Service();        
        $result  = $service->getAllServicesByServiceProviderAndUSerSP( $sp_id, $user->sp_id );
        
        return $result;
    }

    public function listServiceById( $sv_id )
    {
        $service = new Service();
        $result  = $service->getServiceByID($sv_id)['data'];
        $service = json_decode($result)[0];
        
        usort($service->ServiceMatters, function($a, $b){ return strcasecmp($a->MatterName, $b->MatterName); });
        
        $vulnerability_obj = new Vulnerability();
        $vulnertability_questions = $vulnerability_obj->getAllVulnerabilityQuestions();
        $serv_vuln = $service;

        $service_vulnerabilities = [];
        foreach ( $serv_vuln->ServiceVulAnswers as $vulnerability) {            
            $vul_pos = array_search( $vulnerability->QuestionId,  array_column( $vulnertability_questions, 'QuestionId' ) );
            $service_vulnerabilities[] = $vulnertability_questions[$vul_pos]['QuestionLabel'];
        }
        asort($service_vulnerabilities);
        $service->vulnerabilities =  empty($service_vulnerabilities) ? ['None'] : array_values( $service_vulnerabilities );

        $service_catchments = [];
        foreach ( $serv_vuln->ServiceCatchments as $catchments) {
            $service_catchments[$catchments->CatchmentSuburb] = $catchments->CatchmentSuburb;
        }
        asort($service_catchments);
        $service->catchments = array_values($service_catchments);
        
        return response()->json($service);
    }

}
