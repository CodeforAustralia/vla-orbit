<?php
namespace App;

use Illuminate\Support\Facades\Mail;
use App\Mail\ReferralEmail;
use App\Mail\ReferralSms;
use App\Vulnerability;
use Auth;

class Referral
{
    public function getAllReferrals()
    {       
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();

        $user = Auth::user();
        if( $user->sp_id != 0 )
        {
            $info['ServiceProviderId'] = $user->sp_id;
            $referrals = json_decode( 
                                        $client
                                        ->GetAllReferralsByServiceProviderasJSON( $info )
                                        ->GetAllReferralsByServiceProviderasJSONResult, 
                                        true
                                    );
        } 
        else {
            $referrals = json_decode( 
                                    $client
                                    ->GetAllReferralsasJSON()
                                    ->GetAllReferralsasJSONResult, 
                                    true 
                                );
        }

        return $referrals;
    }

    public function getAllReferralsBySP( $sp_id )
    {       
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();

        $info = [ 'ServiceProviderId' => $sp_id];

        $referrals = json_decode( 
                                    $client
                                    ->GetAllReferralsByServiceProviderasJSON( $info )
                                    ->GetAllReferralsByServiceProviderasJSONResult, 
                                    true 
                                );
        return $referrals;
    }

    public function saveReferral( $referral )
    {
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();

        // Current time
        $date_now = date("Y-m-d");
        $time_now = date("H:i:s");
        $date_time = $date_now . "T" . $time_now;

        $referral['CreatedBy'] = auth()->user()->name;
        $referral['UpdatedBy'] = auth()->user()->name;
        $referral['CreatedOn'] = $date_time;
        $referral['UpdatedOn'] = $date_time;
        $referral['Mobile']    = ( $referral['Mobile'] == '' ? '000000000' : $referral['Mobile'] );
        $referral['Email']     = ( $referral['Email'] == '' ? 'N/P' : $referral['Email'] );
        $referral['RefNo']     = 0;
        $referral['SentEmail'] = 0;
        $referral['SentMobile'] = 0;

        $services = session('matches');
        $service = $services[ $referral['ServiceId'] ];
            
        if( $referral['Email'] != '' && $referral['SafeEmail'] != 0 )
        {
            Mail::to( $referral['Email'] )->send( new ReferralEmail( $service ) );
            $referral['SentEmail'] = 1;
        }

        if( $referral['Mobile'] != '' && $referral['SafeMobile'] != 0 )
        {                        
            Mail::to( $referral['Mobile'] . "@e2s.pcsms.com.au"  )->send( new ReferralSms( $service ) );
            $referral['SentMobile'] = 1;
        }

        $info = [ 'ObjectInstance' => $referral ];

        try
        {            
            $response = $client->SaveReferral( $info );
            if( $response->SaveReferralResult )
            {
                return array( 'success' => 'success' , 'message' => 'Service saved.', 'data' => $response->SaveReferralResult );
            } 
            else {
                return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        }
        catch (\Exception $e) 
        {            
            return array( 'success' => 'error' , 'message' =>  $e->getMessage(), 'data' => $referral );       
        }
    }

    public function getServicesByCatchmentId( $ca_id, $mt_id )
    {		
    	// Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();

        $info = [ 'CatchmentId' => $ca_id, 'MatterId' => $mt_id ];

        $services = json_decode( 
            						$client
            						->GetOrbitServicesWithMattersByCatchmentandMatterIdasJSON( $info )
            						->GetOrbitServicesWithMattersByCatchmentandMatterIdasJSONResult, 
            						true 
        						);        
        return $services;
    }

    public function getVulnerabilityByServices( $ca_id, $mt_id )
    {
        $vulnerability_obj = new Vulnerability();
        $vulnertability_questions = $vulnerability_obj->getAllVulnerabilityQuestions();

        $services = self::getServicesByCatchmentId( $ca_id, $mt_id );        
        $qu_id = [];
        $question_list = [];

        foreach ($services as $service) 
        {            
            foreach ($service['ServiceMatters'] as $erviceMatter) 
            {
                foreach ($erviceMatter['VulnerabilityMatterAnswers'] as $vulnerabilityMatterAnswer) 
                {
                    $qu_id[] = $vulnerabilityMatterAnswer['QuestionId'];
                }
            }
            foreach ($service['ServiceVulAnswers'] as $serviceVulAnswer) 
            {
                $qu_id[] = $serviceVulAnswer['QuestionId'];
            }
        }

        foreach ( $vulnertability_questions as $vulnertability_question ) {
            if( in_array( $vulnertability_question['QuestionId'], $qu_id) )
            {
                $question_list[] = $vulnertability_question;
            }
        }
        
        return ['vulnertability_questions' => $question_list, 'service_qty' => count( $services )];
    }

    public function filterServices( $ca_id, $mt_id, $vuln_list )
    {
        $services = self::getServicesByCatchmentId( $ca_id, $mt_id );

        $service_match = false;
        $matches = [];

        foreach ($services as $service) 
        {            
            $service_match = self::matchVulnerability( $service['ServiceVulAnswers'], $vuln_list) ;

            //Not getting information about legal matter and vulnerability
            if( empty( $service['ServiceVulAnswers'] ) && empty( $service['ServiceMatters'] ) )
            {
                $matches[ $service['ServiceId'] ] = $service;
            } 
            elseif( $service_match &&  empty( $service['ServiceMatters'] ) ) 
            {
                $matches[ $service['ServiceId'] ] = $service;
            }

            foreach ($service['ServiceMatters'] as $legal_matter) 
            {
                // if LM does not have vuln on it take the service one 
                if( empty($legal_matter['VulnerabilityMatterAnswers']) && $service_match  ) 
                {
                    $matches[ $service['ServiceId'] ] = $service;
                } 
                elseif( self::matchVulnerability( $legal_matter['VulnerabilityMatterAnswers'], $vuln_list ) )
                {
                    $matches[ $service['ServiceId'] ] = $service;
                } 
                elseif( empty( $service['ServiceVulAnswers'] ) && empty( $legal_matter['VulnerabilityMatterAnswers'] ) )
                {
                    $matches[ $service['ServiceId'] ] = $service;
                }
            }
        }
        //Get list of questions of a legal matter
        $question_list = self::getMatterQuestions( $matches, $mt_id );
        
        session(['matches' => $matches ]);

        return $question_list;
    }

    public function matchVulnerability( $vulnerability, $client_vuln_list )
    {        
        $match = false;
        $service_vuln_list = []; 
        $service_vuln_list = array_column( $vulnerability, "QuestionId" );

        $client_vuln_list = explode( ",", $client_vuln_list );
        $vuln_list_size = sizeof( $client_vuln_list );

        foreach ( $service_vuln_list as $vu_id ) 
        {
            if( in_array( $vu_id, $client_vuln_list ) )
            {
                $match = true;
            } 
        }
        return $match;
    }

    public function getMatterQuestions( $services, $mt_id )
    {        
        $question_list = [];
        foreach ( $services as $service ) 
        {
            if( !empty( $service['ServiceMatters'] ) )
            {
                $matter_pos = array_search( $mt_id,  array_column( $service['ServiceMatters'], 'MatterID' ) );
                $matter_questions = $service['ServiceMatters'][ $matter_pos ]['MatterQuestions'];
                $matter_answers   = $service['ServiceMatters'][ $matter_pos ]['MatterAnswers'];
                foreach ($matter_questions as $question) 
                {
                    $qu_id = $question['QuestionId'];
                    $current_answers = self::getMultipleAnswers( $qu_id, $matter_answers );
                    if( !empty($current_answers) )
                    {
                        if( !isset( $question_list[ $qu_id ]['prop'] ) )
                        {
                            $question_list[ $qu_id ]['prop'] = [
                                                                'Operator'      => $question['Operator'],
                                                                'QuestionName'  => $question['QuestionName'] ,                      
                                                                'QuestionTypeName'  => $question['QuestionTypeName']
                                                                ] ;
                        }
                        if( $question['QuestionTypeName'] == 'multiple' )
                        {
                            if ( !isset( $question_list[ $qu_id ]['prop']['QuestionValue'] ) )
                            {
                                $question_list[ $qu_id ]['prop']['QuestionValue'] = $current_answers ;
                            }
                            else {
                                foreach ( $current_answers as $answer ) {                            
                                    array_push( $question_list[ $qu_id ]['prop']['QuestionValue'], $answer );
                                }
                            }                    
                        }                                                                    
                        $question_list[ $qu_id ]['services'][] = $service['ServiceId'];                
                    }
                }            
            }
                
        }
        return $question_list;
    }

    public function getMultipleAnswers( $qu_id, $matter_answers ) 
    {
        $answers = [];
        foreach ($matter_answers as $answer) 
        {
            if( $answer['QuestionId'] == $qu_id && $answer['QuestionValue'] != ' ' )
            {
                $answers = explode( ',', $answer['QuestionValue'] );
            }
        }
        return $answers;
    }

    public function filterByQuestions( $answers )
    {
        $services = session('matches');
        $matches = [];
        foreach ( $services as $service ) 
        {
            if ( empty( $service['ServiceMatters'] ) ) //not set answers on legal matter inside service, match by default
            {
                $matches[ $service['ServiceId'] ] = $service;
            }

            foreach ( $service['ServiceMatters'] as $legal_matter ) 
            {
                if( self::matchServiceAnswersWithAnswers( $answers, $legal_matter['CommonMatterAnswers'] ) )
                {
                    $matches[ $service['ServiceId'] ] = $service;
                } 
            }
        }

        return $matches;
    }

    public function matchServiceAnswersWithAnswers( $answers, $sericeAnswers )
    {
        $match = true;
        foreach ( $sericeAnswers as $sericeAnswer ) //each question by matter
        {
            $sva_id = $sericeAnswer['QuestionId'];
            if( isset( $answers[ $sva_id ] ) ) //was the question answered?
            {    
                $sericeAnswer['answer'] = $answers[ $sva_id ];
            } 
            else {
                $sericeAnswer['answer'] = " ";
            }
            
            if( !self::compareQuestionAnswer( $sericeAnswer ) ) // not apply for this service
            {
                $match = false;
            }
        }
        return $match;
    }

    public function compareQuestionAnswer( $args )
    {
        switch ($args['Operator']) 
        {
            case '>':
                return ( $args['answer'] > $args["QuestionValue"] );
                break;
            case '>=':
                return ( $args['answer'] >= $args["QuestionValue"] );
                break;
            case '<':
                return ( $args['answer'] < $args["QuestionValue"] );
                break;
            case '<=':
                return ( $args['answer'] <= $args["QuestionValue"] );
                break;
            case '=':
                return ( $args['answer'] == $args["QuestionValue"] );
                break;      
            case 'in':
                $options = explode( ',', $args['QuestionValue']);     
                return ( in_array( $args['answer'], $options) );
                break;            
            default:
                # Check empty values in question because the answer was not answered
                # Does not matter the answer to this question is always true
                return ( $args["answer"] == " " || $args["QuestionValue"] == " " );
                break;
        }
    }
}