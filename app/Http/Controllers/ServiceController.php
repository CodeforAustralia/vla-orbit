<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service;
use App\ServiceType;
use App\ServiceLevel;
use App\ServiceProvider;
use App\Matter;
use App\MatterService;
use App\Catchment;
use App\Vulnerability;
use App\MatterServiceAnswer;

class ServiceController extends Controller
{    
    public function index()
    {
        return view("service.index");
    }

    public function show( $sv_id )
    {

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

            return view( "service.show", compact( 'current_service', 'service_types', 'service_levels', 'service_providers', 'matters', 'matter_services' , 'catchments', 'vulnertability_questions', 'current_vulnerabilities' ) );        	
        } else {
        	return redirect('/service')->with( $response['success'], $response['message'] );
        }        
    }
    

    public function store()
    {        
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

            if( !empty( request('vulnerability') ) ) 
            {
                $vul_questions = array_keys( request('vulnerability') );                
                $vulnerability_obj = new Vulnerability();
                // Delete previous vul. answers
                $vulnerability_obj->deleteVulnerabilityByServiceID( $sv_id );    
                // Save vul. answers          
                $vulnerability_obj->saveVulnerabilityQuestions( $sv_id, $vul_questions );                
            }

            if( !empty( request('question') ) )
            {
                $matter_service_answer = new MatterServiceAnswer();            
                $matter_service_answer->processMatterServiceAnswer( request('question'), $sv_id ); 
                $matter_service_answer->processVulnerabilityMatterServiceAnswer( request('vulnerability_matter'), $sv_id );
            }
        }
        
        return redirect('/service')->with( $response['success'], $response['message'] );
    }
    
    public function create()
    {
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

        return view( "service.create", compact( 'service_types','service_levels','service_providers', 'matters', 'vulnertability_questions' ) );
    }

    public function destroy($sv_id)
    {
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

}
