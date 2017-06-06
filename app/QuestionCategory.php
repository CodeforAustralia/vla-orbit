<?php
namespace App;

Class QuestionCategory
{
    public function getAllQuestionCategories()
    {
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();
        
        $question_categories = json_decode( $client->GetAllQuestionCategoriesasJSON()->GetAllQuestionCategoriesasJSONResult, true );

        return $question_categories;
    }


    public function saveQuestionCategory( $question_category ) 
    {
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();
        
        // Current time
        $date_now = date("Y-m-d");
        $time_now = date("H:i:s");
        $date_time = $date_now . "T" . $time_now;

        $question_category['CreatedBy'] = auth()->user()->name;
        $question_category['UpdatedBy'] = auth()->user()->name;
        $question_category['CreatedOn'] = $date_time;
        $question_category['UpdatedOn'] = $date_time;

        // Create call request        
        $info = [ 'ObjectInstance' => $question_category ];
        
        try {
            $response = $client->SaveQuestionCategoy( $info );
            
            if($response->SaveQuestionCategoyResult){
                return array( 'success' => 'success' , 'message' => 'Question Category saved.' );
            } else {
                return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        }
        catch (\Exception $e) {            
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );      
        }
    }

    public function deleteQuestionCategory( $qc_id )
    {    	
        // Create Soap Object
        $client =  (new \App\Repositories\VlaSoap)->ws_init();
        
        // Create call request        
        $info = [ 'RefNumber' => $qc_id ];

        try {
            $response = $client->DeleteQuestionCategoy( $info );
            if( $response->DeleteQuestionCategoyResult ){
                return array( 'success' => 'success' , 'message' => 'Question Category deleted.' );
            } else {
                return array( 'success' => 'error' , 'message' => 'Ups, something went wrong.' );
            }
        }
        catch (\Exception $e) {            
            return array( 'success' => 'error' , 'message' =>  $e->getMessage() );       
        }
    }
}