<?php
namespace App;

Class MatterQuestion
{

    public function saveMatterQuestion( $matter_question ) 
    {
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();
        
        // Current time     
        $date_now = date("Y-m-d");
        $time_now = date("H:i:s");
        $date_time = $date_now . "T" . $time_now;

        $matter_question['CreatedBy'] = auth()->user()->name;     
        $matter_question['UpdatedBy'] = auth()->user()->name;     
        $matter_question['CreatedOn'] = $date_time;       
        $matter_question['UpdatedOn'] = $date_time;    

        // Create call request        
        $info = [ 'ObjectInstance' => $matter_question ];
        
        try {

            $response = $client->SaveMatterQuestions( $info );
            
            if( $response->SaveMatterQuestionsResult ){
                return array( 'success' => 'success' , 'message' => 'Matter question saved.' );
            } else {
                return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        }
        catch (\Exception $e) {            
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );      
        }
    }

    public function processMatterQuestions( $questions, $mt_id )
    {
        self::deleteMatterQuestionsByMatterID( $mt_id );
        
        foreach ( $questions as $qu_id => $qu_values ) {
            if( isset( $qu_values['check'] ) )
            {
                if( is_null( $qu_values['operator'] ) || is_null( $qu_values['answer'] ) ) 
                {     
                    $qu_values['operator'] = '';
                    $qu_values['answer'] = '';
                }               
                self::saveMatterQuestion( [
                                            'MatterId' => $mt_id,
                                            'QuestionId' => $qu_id,
                                            'Operator' => $qu_values['operator'],
                                            'QuestionValue' => $qu_values['answer']
                                           ] 
                                        );                
            }
        }
    }

    public function deleteMatterQuestionsByMatterID( $mt_id ) //TO be fixed by IT
    {
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();

        // Create call request        
        $info = [ 'MatterId' => $mt_id ];
        
        try {
            $response = $client->DeleteMatterQuestionsByMatterId( $info );
            
            if( $response->DeleteMatterQuestionsByMatterIdResult ){
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