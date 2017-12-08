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

    public function getAllOutboundReferrals()
    {       
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();

        $user = Auth::user();
        if( $user->sp_id != 0 )
        {
            $info['OutServiceProviderId'] = $user->sp_id;
            $referrals = json_decode( 
                                        $client
                                        ->GetAllReferralsByOutServiceProviderasJSON( $info )
                                        ->GetAllReferralsByOutServiceProviderasJSONResult, 
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

    public function saveReferral( $referral, $service_provider )
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
        $referral['Notes']     = ( ( !isset( $referral['Notes'] ) || $referral['Notes'] === '') ? 'N/P' : $referral['Notes'] );
        $referral['RefNo']     = 0;
        $referral['SentEmail'] = 0;
        $referral['SentMobile'] = 0;
        $referral['ReferralAnswers'] = self::getAnswersFromSession();

        $services = session('matches');
        $service = $services[ $referral['ServiceId'] ];
        $service['sendingServiceProvider'] = $service_provider;        
            
        if( $referral['Email'] != 'N/P' && $referral['SafeEmail'] != 0 )
        {
            $referral['SentEmail'] = 1;
        }

        if( $referral['Mobile'] != '' && $referral['SafeMobile'] != 0 )
        {                        
            $referral['SentMobile'] = 1;
        }

        $info = [ 'ObjectInstance' => $referral ];

        try
        {            
            $response = $client->SaveReferral( $info );
            if( $response->SaveReferralResult )
            {
                $service['RefNo'] = $response->SaveReferralResult;
                if( $referral['Email'] != 'N/P' && $referral['SafeEmail'] != 0 )
                {
                    Mail::to( $referral['Email'] )->send( new ReferralEmail( $service ) );
                }

                if( $referral['Mobile'] != '' && $referral['SafeMobile'] != 0 )
                {                        
                    Mail::to( $referral['Mobile'] . "@e2s.pcsms.com.au"  )->send( new ReferralSms( $service ) );
                }
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

    public function getAnswersFromSession()
    {
        $referral_answers = [];
        //eligibility
        $vls = explode(',', session('vls_id') );

        if( !empty( $vls ) )
        {
            foreach ($vls as $eligibility) {
                $referral_answers[] =  [
                                            'Answer' => true,
                                            'QuestionId' => $eligibility,
                                            'RefNo' => 0 ,
                                            'ReferrelId' => 0
                                        ];  
            }
        }

        //questions
        $answers = sizeof( session('answers') );
        if( $answers > 0 )
        {
            foreach (session('answers') as $answer_id => $answer) {
                $referral_answers[] = [
                                        'Answer' => $answer,
                                        'QuestionId' => $answer_id,
                                        'RefNo' => 0 ,
                                        'ReferrelId' => 0
                                    ];  
            }
        }
        return $referral_answers;
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

        //Define weights
        $vulnerability_w = config('referral.vulnerability');

        foreach ($services as $service) 
        {
            //Global service match
            $service_match = self::matchVulnerability( $service['ServiceVulAnswers'], $vuln_list);

            //Each LM inside a service
            foreach ($service['ServiceMatters'] as $legal_matter) 
            {
                if( $legal_matter['MatterID'] == $mt_id )
                {        
                    // No answers and fit the global eligib.
                    if( empty( $legal_matter['VulnerabilityMatterAnswers'] ) && $service_match )
                    {
                        // global eligibility match against vuln list
                        if( $vuln_list != '' && !empty( $service['ServiceVulAnswers'] ) )
                        {                            
                            $service['sort']['eligibility'][] = $legal_matter;                            
                            $service['sort']['weight'] = 
                            ( isset($service['sort']['weight']) ?  $service['sort']['weight'] + $vulnerability_w : $vulnerability_w);
                        }
                        $matches[ $service['ServiceId'] ] = $service;
                    } 
                    // Answered/checked eligibility on specific LM and match vuln list.
                    elseif ( !empty( $legal_matter['VulnerabilityMatterAnswers'] ) && $vuln_list != '' 
                        && self::matchVulnerability( $legal_matter['VulnerabilityMatterAnswers'], $vuln_list) ) 
                    {                        
                        $service['sort']['eligibility'][] = $legal_matter;
                        $service['sort']['weight'] = 
                        ( isset($service['sort']['weight']) ?  $service['sort']['weight'] + $vulnerability_w : $vulnerability_w);
                        
                        $matches[ $service['ServiceId'] ] = $service;
                    }
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
        if( empty( $vulnerability ) )
        {
            $match = true;
        }
        else
        {
            foreach ( $service_vuln_list as $vu_id ) 
            {
                if( in_array( $vu_id, $client_vuln_list ) )
                {
                    $match = true;
                } 
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
                                    array_push( $question_list[ $qu_id ]['prop']['QuestionValue'], trim( $answer ) );
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

    public function filterByQuestions( $answers, $mt_id )
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
                $match_sa = self::matchServiceAnswersWithAnswers( $answers, $legal_matter['CommonMatterAnswers'] );
                if( $match_sa['match'] && $legal_matter['MatterID'] == $mt_id )
                {
                    if( !empty( $legal_matter['CommonMatterAnswers'] ) )
                    {
                        $service['sort']['questions'][] = $legal_matter;
                        $service['sort']['weight'] = ( isset($service['sort']['weight']) ?  $service['sort']['weight'] +  $match_sa['weight']  :  $match_sa['weight'] );                        
                    }
                    $matches[ $service['ServiceId'] ] = $service;
                } 
            }
        }

        return $matches;
    }

    public function matchServiceAnswersWithAnswers( $answers, $sericeAnswers )
    {
        $weight = 0; // For questions that are not answered is false
        $match  = true;        
        //Define weights
        $question_w = config('referral.question');
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
            elseif( $sericeAnswer['QuestionValue'] != ' ' && self::compareQuestionAnswer( $sericeAnswer ) )
            {
                $weight += $question_w;
            }
        }
        return [ 'match' => $match, 'weight' => $weight ];
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
                foreach ($options as $answer) {
                    if( trim( strtolower($answer) ) == trim( strtolower($args['answer']) ) )
                    {
                        return true;
                    }
                }
                return false;
                break;            
            default:
                # Check empty values in question because the answer was not answered
                # Does not matter the answer to this question is always true
                return ( $args["answer"] == " " || $args["QuestionValue"] == " " );
                break;
        }
    }

    public function sortMatches( $services )
    {
        //Define weights
        $catcment_w = config('referral.catchment');
        $legal_help_w = config('referral.legal_help');
        $matches = [];
        foreach ( $services as $key => $service ) 
        {
            // is LH
            $sp_type_name =  $service['ServiceProviderTypeName'];
            if( $sp_type_name == 'Legal Help' ) // is Legal Help
            {
                $service['sort']['is_lh']  = true;
                $service['sort']['weight'] = ( isset($service['sort']['weight']) ?  $service['sort']['weight'] + $legal_help_w : $legal_help_w);
            }

            // Catchment
            $catchments = $service['ServiceCatchments'];
            foreach ( $catchments as $catchment ) 
            {
                if( $catchment['CatchmentPostcode'] != 0 )
                {
                    $service['sort']['weight'] = 
                    ( isset($service['sort']['weight']) ? $service['sort']['weight'] + $catcment_w : $catcment_w);
                }
            }
            $matches[$key] = $service;
        }

        array_multisort(
            array_map(function($element) {
                if( isset( $element['sort']['weight'] ) ){                    
                    return $element['sort']['weight'];
                } else {
                    return 0;
                }
            }, $matches) 
        , SORT_DESC, $matches);

        return $matches;
    }

}
