<?php
namespace App;

Class QuestionType
{
    public function GetAllQuestionTypes()
    {
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();
        
        $question_types = json_decode($client->GetAllQuestionTypessasJSON()->GetAllQuestionTypessasJSONResult, true);

        return $question_types;
    }


    public function saveQuestionType( $question_type ) 
    {
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();
        
        // Current time
        $date_now = date("Y-m-d");
        $time_now = date("H:i:s");
        $date_time = $date_now . "T" . $time_now;

        $question_type['CreatedBy'] = auth()->user()->name;
        $question_type['UpdatedBy'] = auth()->user()->name;
        $question_type['CreatedOn'] = $date_time;
        $question_type['UpdatedOn'] = $date_time;

        // Create call request        
        $info = [ 'ObjectInstance' => $question_type ];
        
        try {
            $response = $client->SaveQuestionType( $info );
            
            if($response->SaveQuestionTypeResult){
                return array( 'success' => 'success' , 'message' => 'Question Type saved.' );
            } else {
                return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        }
        catch (\Exception $e) {            
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );      
        }
    }

    public function deleteQuestionType( $qt_id )
    {    	
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();
        
        // Create call request        
        $info = [ 'RefNumber' => $qt_id ];

        try {
            $response = $client->DeleteQuestionType( $info );
            if($response->DeleteQuestionTypeResult){
                return array( 'success' => 'success' , 'message' => 'Question Type deleted.' );
            } else {
                return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        }
        catch (\Exception $e) {            
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );       
        }
    }
}