<?php
namespace App;

Class Question
{
    public function getAllQuestions()
    {
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();
        
        $questions = json_decode($client->GetAllQuestionsasJSON()->GetAllQuestionsasJSONResult, true);

        return $questions;
    }

    public function getAllQuestionById( $qu_id )
    {
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();

        try {
            $question = json_decode( $client->GetAllQuestionsByIdasJSON( array( 'RefNumber' => $qu_id ) )->GetAllQuestionsByIdasJSONResult );
            return array( 'success' => 'success' , 'message' => 'Service.', 'data' => $question );
        }
        catch (\Exception $e) {            
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );       
        }

    }

    public function saveQuestion( $question ) 
    {
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();
        
        // Current time
        $date_now = date("Y-m-d");
        $time_now = date("H:i:s");
        $date_time = $date_now . "T" . $time_now;

        $question['CreatedBy'] = auth()->user()->name;
        $question['UpdatedBy'] = auth()->user()->name;
        $question['CreatedOn'] = $date_time;
        $question['UpdatedOn'] = $date_time;

        // Create call request        
        $info = [ 'ObjectInstance' => $question ];
        
        try {
            $response = $client->SaveQuestion($info);
            
            if($response->SaveQuestionResult){
                return array( 'success' => 'success' , 'message' => 'Question saved.' );
            } else {
                return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        }
        catch (\Exception $e) {
            dd($info, $e);
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );      
        }
    }

    public function deleteQuestion( $qu_id )
    {    	
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();
        
        // Create call request        
        $info = [ 'RefNumber' => $qu_id ];

        try {
            $response = $client->DeleteQuestion( $info );
            if($response->DeleteQuestionResult){
                return array( 'success' => 'success' , 'message' => 'Question deleted.' );
            } else {
                return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        }
        catch (\Exception $e) {            
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );       
        }
    }
}