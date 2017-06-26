<?php
namespace App;

class Referral
{

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

    public function filterServices( $ca_id, $mt_id, $vuln_list )
    {
        $services = self::getServicesByCatchmentId( $ca_id, $mt_id );

        $service_match = false;
        $matches = [];

        foreach ($services as $service) 
        {
            $service_match = self::matchVulnerability( $service['ServiceVulAnswers'], $vuln_list) ;
            foreach ($service['ServiceMatters'] as $legal_matter) 
            {
                // if LM does not have vuln on it take the service one 
                if( empty($legal_matter['VulnerabilityMatterAnswers']) && $service_match  ) 
                {
                    $matches[] = $service;
                } 
                elseif( self::matchVulnerability( $legal_matter['VulnerabilityMatterAnswers'], $vuln_list ) )
                {
                    $matches[] = $service;
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
        $match = true;
        $service_vuln_list = []; 
        $service_vuln_list = array_column( $vulnerability, "QuestionId" );

        $client_vuln_list = explode( ",", $client_vuln_list );
        $vuln_list_size = sizeof( $client_vuln_list );

        foreach ( $service_vuln_list as $vu_id ) 
        {
            if( !in_array( $vu_id, $client_vuln_list ) )
            {
                $match = false;
            } 
        }
        return $match;
    }

    public function getMatterQuestions( $services, $mt_id )
    {
        $question_list = [];
        
        foreach ( $services as $service ) 
        {
            $matter_pos = array_search( $mt_id,  array_column( $service['ServiceMatters'], 'MatterID' ) );
            $matter_questions = $service['ServiceMatters'][ $matter_pos ]['MatterQuestions'];
            foreach ($matter_questions as $question) {                    
                $question_list[ $question['QuestionId'] ]['prop'] = [
                                                                    'Operator'      => $question['Operator'],
                                                                    'QuestionValue' => $question['QuestionValue'],
                                                                    'QuestionName'  => $question['QuestionName'] ,                      
                                                                    'QuestionTypeName'  => $question['QuestionTypeName']                       
                                                                    ] ;
                $question_list[ $question['QuestionId'] ]['services'][] = $service['ServiceId'];
                
            }
            
        }
        return $question_list;
    }

    public function filterByQuestions( $answers )
    {
        $services = session('matches');
        $matches = [];
        foreach ( $services as $service ) 
        {
            foreach ( $service['ServiceMatters'] as $legal_matter ) 
            {
                if( self::matchServiceAnswersWithAnswers( $answers, $legal_matter['CommonMatterAnswers'] ) )
                {
                    $matches[] = $service;
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
            default:
                # Check empty values in question because the answer was not answered
                # Does not matter the answer to this question is always true
                return ( $args["QuestionValue"] == " " || $args["QuestionValue"] == " " );
                break;
        }
    }
}