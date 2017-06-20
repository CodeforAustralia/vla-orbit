<?php
namespace App;

use App\Service;

Class MatterServiceAnswer
{

    public function saveMatterServiceAnswer( $matter_question ) 
    {
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();
        
        // Create call request        
        $info = [ 'ObjectInstance' => $matter_question ];
        
        try {

            $response = $client->SaveServiceMatterAnswers( $info );
            
            if( $response->SaveServiceMatterAnswersResult ){
                return array( 'success' => 'success' , 'message' => 'Matter question saved.' );
            } else {
                return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        }
        catch (\Exception $e) {           
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );      
        }
    }

    public function processMatterServiceAnswer( $questions, $sv_id )
    {
        self::deleteMatterAnswerByServiceID( $sv_id );

        $service = new Service();
        $result  = $service->getServiceByID( $sv_id ); //Get the last version of that service

        if(isset($result['data'])) 
        {
            $current_service = json_decode( $result['data'] )[0];

            foreach ( $questions as $qu_id => $qu_values ) 
            {
                if( is_null( $qu_values['operator'] ) || is_null( $qu_values['answer'] ) ) 
                {
                    $qu_values['operator'] = ' ';
                    $qu_values['answer'] = ' ';
                }
                //Get service matter position of question in current service matter - Potentially slow for huge arrays
                $sm_position = array_search( 
                                                $qu_values['mt_id'],  //Legal matter id
                                                array_column(
                                                    $current_service->ServiceMatters,  // Array of matters in a service
                                                    'MatterID' //Column to search an specific matter
                                                )
                                              );
                
                $lms_id = $current_service->ServiceMatters[$sm_position]->MatterServiceID;

                self::saveMatterServiceAnswer( [
                                            'RefNo'           => 0,
                                            'MatterServiceId' => $lms_id,
                                            'QuestionId'      => $qu_id,
                                            'Operator'        => $qu_values['operator'],
                                            'QuestionValue'   => $qu_values['answer']
                                           ] 
                                        );
                
            }

        }
        
    }

    public function processVulnerabilityMatterServiceAnswer( $questions, $sv_id )
    {

        $service = new Service();
        $result  = $service->getServiceByID( $sv_id ); //Get the last version of that service

        if(isset($result['data'])) 
        {
            $current_service = json_decode( $result['data'] )[0];

            foreach ( $questions as $lm_id => $qu_ids ) 
            {
                
                $operator = '=';
                $answer = true;
                
                //Get service matter position of question in current service matter - Potentially slow for huge arrays
                $sm_position = array_search( 
                                                $lm_id,  //Legal matter id
                                                array_column(
                                                    $current_service->ServiceMatters,  // Array of matters in a service
                                                    'MatterID' //Column to search an specific matter
                                                )
                                              );
                
                $lms_id = $current_service->ServiceMatters[$sm_position]->MatterServiceID;
                foreach ($qu_ids as $qu_id => $value) {                    
                    self::saveMatterServiceAnswer( [
                                                'RefNo'           => 0,
                                                'MatterServiceId' => $lms_id,
                                                'QuestionId'      => $qu_id,
                                                'Operator'        => $operator,
                                                'QuestionValue'   => $answer
                                               ] 
                                            );
                }
                
            }

        }
        
    }

    public function deleteMatterAnswerByServiceID( $sv_id )
    {
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();

        // Create call request        
        $info = [ 'ServiceId' => $sv_id ];
        
        try {
            $response = $client->DeleteMatterAnswersByServiceId( $info );
            
            if( $response->DeleteMatterAnswersByServiceIdResult ){
                return array( 'success' => 'success' , 'message' => 'Matter question deleted.' );
            } else {
                return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        }
        catch (\Exception $e) {            
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );      
        }
    }
}